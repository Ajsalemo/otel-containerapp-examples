import { trace } from "@opentelemetry/api";
import express from "express";
import { logger } from "../otel/instrumentation.js";

const router = express.Router();

export const homeController = router.get("/", (_req, res) => {
    const tracer = trace.getActiveSpan();

    tracer.setAttribute('function', 'homeController');
    tracer.addEvent('Request received');
    logger.emit({ severityText: 'INFO', severityNumber: 9, body: 'Request received' })
    try {
        res.json({ "msg": "container-apps-health-probe-examples-node-http" })
        logger.emit({ severityText: 'INFO', severityNumber: 9, body: 'Response sent' })
        tracer.addEvent('Response sent');
    } catch (error) {
        console.error(error)
        res.json({ "err": error })
        logger.emit({ severityText: 'ERROR', severityNumber: 17, body: error })
        tracer.addEvent('Error occurred');
    }
})