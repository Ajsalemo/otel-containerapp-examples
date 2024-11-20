import express from "express";

const router = express.Router();

export const reqHeadersController = router.get("/", (req, res) => {
    try {
        console.log(req.headers)
        res.json({ "msg": "Logging to console.." })
    } catch (error) {
        console.error(error)
        res.json({ "err": error })
    }
})