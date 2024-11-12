import { Boom } from "@hapi/boom"
import baileys from "@whiskeysockets/baileys"
import fs from "fs"
import P from "pino"
import QRCode from "qrcode"
import readline from "readline"
import logger from "./app/logger.js"
import formatReceipt from "./lib/formatReceipt.js"

const {
    makeWASocket,
    BinaryInfo,
    delay,
    DisconnectReason,
    downloadAndProcessHistorySyncNotification,
    encodeWAM,
    fetchLatestBaileysVersion,
    getAggregateVotesInPollMessage,
    getHistoryMsg,
    isJidNewsletter,
    makeCacheableSignalKeyStore,
    makeInMemoryStore,
    proto,
    useMultiFileAuthState,
    Mimetype
} = baileys

const rl = readline.createInterface({ input: process.stdin, output: process.stdout })

const pino = P({ timestamp: () => `,"time":"${new Date().toJSON()}"` })
pino.level = 'trace'

let sock = []
let qrcode = []
let intervalStore = []

async function connectToWhatsApp(sender, io = null) {
    const { state, saveCreds } = await useMultiFileAuthState(`./credentials/${sender}`)

    sock[sender] = makeWASocket({
        browser: ['AyasyaTech', "Chrome", ""],
        logger: pino,
        printQRInTerminal: true,
        auth: {
            creds: state.creds,
            keys: makeCacheableSignalKeyStore(state.keys, pino)
        }
    })

    sock[sender].ev.process(async (events) => {
        if (events["connection.update"]) {
            const update = events["connection.update"]
            const { connection, lastDisconnect, qr } = update

            if (connection === 'connecting') {
                if (sock[sender].user) {
                    logger("info", `Reconnecting`, `${sock[sender].user.id.split(":")[0]}`)
                }
            }

            if (qr) {
                QRCode.toDataURL(qr, function (err, url) {
                    if (err) {
                        console.log(err)
                    }
                    qrcode[sender] = url
                    if (io !== null) {
                        io.emit("qrcode", {
                            sender,
                            data: url,
                            message: "Please scan with your Whatsapp Account",
                        })
                    }
                })
            }

            if (connection === 'open') {
                logger("primary", "Connected", sock[sender].user.id.split(":")[0])

                if (io !== null) {
                    io.emit("message", {
                        sender,
                        rc: 9,
                        message: "Connected",
                        user: sock[sender].user,
                    })
                }
                delete qrcode[sender]
            }

            if (connection === "close") {
                if ((lastDisconnect?.error instanceof Boom)?.output?.statusCode !== DisconnectReason.loggedOut) {
                    delete qrcode[sender]
                    if (io != null) {
                        io.emit("message", {
                            rc: 0,
                            sender: sender,
                            message: "Connecting..",
                        })
                    }
                    if (lastDisconnect.error?.output?.payload?.message === "Connection Closed") {
                        connectToWhatsApp(sender, io)
                    }

                    if (lastDisconnect.error?.output?.payload?.message === "QR refs attempts ended") {
                        delete qrcode[sender]
                        sock[sender].ws.close()
                        if (io != null)
                            io.emit("message", {
                                rc: 1,
                                sender: sender,
                                message:
                                    "Request QR ended. reload scan to request QR again",
                            })
                        return
                    }
                    if (lastDisconnect?.error.output.payload.message != "Stream Errored (conflict)") {
                        connectToWhatsApp(sender, io)
                    }
                } else {
                    logger("error", "closed", "Connection closed. You are logged out.")
                    fs.rmSync(`./credentials/${sender}`, { recursive: true, force: true });
                    if (io !== null) {
                        io.emit("message", {
                            rc: 3,
                            sender,
                            message: "Connection closed. You are logged out.",
                        })
                    }
                    clearConnection(sender)
                }
            }
        }

        if (events["creds.update"]) {
            const creds = events["creds.update"]
            saveCreds(creds)
        }

    })

    return {
        sock: sock[sender],
        qrcode: qrcode[sender]
    }
}

function clearConnection() {
    clearInterval(intervalStore[sender])

    delete sock[sender]
    delete qrcode[sender]
    if (fs.existsSync(`./credentials/${sender}`)) {
        fs.rmSync(
            `./credentials/${sender}`,
            { recursive: true, force: true },
            (err) => {
                if (err) console.log(err)
            }
        )
        console.log(`credentials/${sender} is deleted`)
    }
}

async function connectWaBeforeSend(sender) {
    let status = undefined
    let connect
    connect = await connectToWhatsApp(sender)

    await connect.sock.ev.on("connection.update", (con) => {
        const { connection, qr } = con
        if (connection === "open") {
            status = true
        }
        if (qr) {
            status = false
        }
    })

    let counter = 0
    while (typeof status === "undefined") {
        counter++
        if (counter > 4) {
            break
        }
        await new Promise((resolve) => setTimeout(resolve, 1000))
    }

    return status
}

async function sentMessage(sender, receiver, text, io) {
    const receiverFormatted = formatReceipt(receiver)

    const check = await isExist(sender, formatReceipt(receiver))
    if (!check) {
        io.emit('message', {
            sender,
            success: false,
            message: "The destination Number not registered in WhatsApp or your sender not connected.",
        })
    }
    return sock[sender].sendMessage(receiverFormatted, { text })
}

async function initialize(sender) {
    const path = `./credentials/${sender}`


    if (fs.existsSync(path)) {
        sock[sender] = undefined
        return connectWaBeforeSend(sender)

    }
}

async function disconnecDevice(sender, io = null) {
    if (io !== null) {
        io.emit("message", {
            sender,
            message: "Logout Progress",
        })
    }

    // Pastikan sender dalam bentuk string
    const senderId = sender.sender

    if (typeof sock[senderId] === "undefined") {
        const status = await connectToWhatsApp(senderId)
        if (status) {
            await sock[senderId].logout()
            delete sock[senderId]
        }
    } else {
        await sock[senderId].logout()
        delete sock[senderId]
    }

    delete sock[senderId]
    clearInterval(intervalStore[senderId])

    if (io != null) {
        io.emit("Unauthorized", senderId)
        io.emit("message", {
            sender: senderId,
            message: "Connection closed. You are logged out.",
        })
    }

    try {
        const path = `./credentials/${senderId}`
        if (fs.existsSync(path)) {
            fs.rmSync(path, { recursive: true, force: true })
            console.log(`Deleted credentials folder for sender: ${senderId}`)
        } else {
            console.log(`Credentials folder for sender ${senderId} not found`)
        }
    } catch (error) {
        console.error(`Error deleting credentials for sender ${senderId}:`, error)
    }

    return {
        status: true,
        message: "Deleting session and credential",
    }
}

async function deleteDevice(sender, io = null) {
    // Pastikan sender dalam bentuk string
    const senderId = sender.sender
    try {
        await sock[senderId].logout()
        delete sock[senderId]

        const path = `./credentials/${senderId}`
        if (fs.existsSync(path)) {
            fs.rmSync(path, { recursive: true, force: true })
            console.log(`Deleted credentials folder for sender: ${senderId}`)
        } else {
            console.log(`Credentials folder for sender ${senderId} not found`)
        }
    } catch (error) {
        console.error(`Error deleting credentials for sender ${senderId}:`, error)
    }

    io.emit("message", {
        message: "Success",
    })
}

async function isExist(sender, number) {
    if (typeof sock[sender] === "undefined") {
        const status = await connectWaBeforeSend(sender)
        if (!status) {
            return false
        }
    }
    try {
        if (number.includes("@g.us")) {
            return true
        } else {
            const [result] = await sock[sender].onWhatsApp(number)

            return result
        }
    } catch (error) {
        return false
    }
}

export {
    clearConnection,
    connectToWhatsApp,
    connectWaBeforeSend, deleteDevice, disconnecDevice,
    initialize, isExist, sentMessage
}

