import express from "express"
import sendMessageC from "../controller/whatsappController.js"
import { checkDestination } from "../middleware/middleware.js"

const router = new express.Router()

router.get('*', (req, res) => {
    res.status(404).json({ message: "Page not found" })
})

// Message
router.post("/api/v1/message/single", checkDestination, sendMessageC)

export {
    router
}

