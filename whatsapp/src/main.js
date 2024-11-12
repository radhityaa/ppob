import 'dotenv/config.js'
import { Server } from 'socket.io'
import { connectToWhatsApp, deleteDevice, disconnecDevice, sentMessage } from './wa.js'
import { web } from './app/web.js'
import logger from './app/logger.js'

const PORT = 4000

const io = new Server(5000, {
    cors: {
        origin: ["http://127.0.0.1:8000", "http://localhost:8000"],
        methods: "*",
        credentials: true
    }
})

io.on('connection', (socket) => {
    socket.emit('connected', { message: 'Connected to the server' })

    socket.on('startConnection', (data) => {
        const { sender } = data
        connectToWhatsApp(sender, io)
    })

    socket.on('sendMessage', (data) => {
        const { sender, receiver, text } = data
        sentMessage(sender, receiver, text, io)
    })

    socket.on('logoutDevice', (data) => {
        const { sender } = data
        disconnecDevice(sender, io)
    })

    socket.on('deleteDevice', (device) => {
        deleteDevice(device, io)
    })
})

web.listen(PORT, () => {
    logger('info', 'server', `Server runnning on port ${PORT}...`)
})
