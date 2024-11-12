import express from "express"
import { errorMiddleware } from "../middleware/errorMiddleware.js"
import { router } from "../route/index.js"
import cors from "cors"

export const web = express()
web.use(express.json())
web.use(cors({
    origin: ['https://ayasyatech.com'],
    credentials: true,
    methods: ['POST'],
}))

web.use(router)
web.use(errorMiddleware)
