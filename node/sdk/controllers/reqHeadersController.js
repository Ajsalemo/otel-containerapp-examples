import { trace } from "@opentelemetry/api";
import { logger } from "../otel/instrumentation.js";
import express from "express";

const router = express.Router();

export const reqHeadersController = router.get("/", (req, res) => {
    const tracer = trace.getActiveSpan();

    tracer.setAttribute('function', 'reqHeadersController');
    tracer.addEvent('Request received');
    logger.emit({ severityText: 'INFO', severityNumber: 9, body: 'Request received' })
    try {
        console.log(req.headers)
        res.json({ "msg": "Logging to console.." })
        logger.emit({ severityText: 'INFO', severityNumber: 9, body: 'Logging request headers' })
        logger.emit({ severityText: 'INFO', severityNumber: 9, body: req.headers })
        logger.emit({ severityText: 'INFO', severityNumber: 9, body: 'Response sent' })
        tracer.addEvent('Response sent');
    } catch (error) {
        console.error(error)
        res.json({ "err": error })
        logger.emit({ severityText: 'ERROR', severityNumber: 17, body: error })
        tracer.addEvent('Error occurred');
    }
})