import responseSuccess from "../lib/responseSuccess.js"
import { sentMessage } from "../wa.js"

export default async function sendMessageC(req, res, next) {
    const { sender, receiver, text } = req.body

    try {
        if (!text) {
            return res.status(400).json({
                success: false,
                message: 'Text is required'
            })
        }

        const result = await sentMessage(sender, receiver, text)
        return responseSuccess(res, 'Message Has Been Sending', result)
    } catch (e) {
        next(e)
    }
}
