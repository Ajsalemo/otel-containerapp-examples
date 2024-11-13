import express from "express";

const router = express.Router();

export const homeController = router.get("/", (_req, res) => {
    try {
        res.json({ "msg": "otel-sdk-examples-node-codeless" })
    } catch (error) {
        console.error(error)
        res.json({ "err": error })
    }
})