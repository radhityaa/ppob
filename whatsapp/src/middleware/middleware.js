import formatReceipt from "../lib/formatReceipt.js";
import { isExist } from "../wa.js"

const checkDestination = async (req, res, next) => {
    const { sender, receiver } = req.body;

    if (!sender) {
        return res.status(400).json({
            success: false,
            message: "The sender is required",
        })
    }

    if (!receiver) {
        return res.status(400).json({
            success: false,
            message: "The receiver is required",
        })
    }

    const check = await isExist(sender, formatReceipt(receiver));
    if (!check) {
        return res.send({
            success: false,
            message:
                "The destination Receiver not registered in WhatsApp or your sender not connected",
        });
    }
    next();

}

export {
    checkDestination
}
