// Import instrumentation.js at the very start so `sdk.start()` is called
// Or else instrumentation will not happen
import "./otel/instrumentation.js";
import { logger } from "./otel/instrumentation.js";
import { trace } from "@opentelemetry/api";

import express from "express";
import { homeController } from "./controllers/homeController.js";
import { reqHeadersController } from "./controllers/reqHeadersController.js";

const tracer = trace.getTracer('server', '0.1.0');

const port = process.env.PORT || 3000;
const app = express()

app.use(homeController)
app.use("/api/reqheaders", reqHeadersController)

app.listen(port, () => {
    return tracer.startActiveSpan('startup', (span) => {
        console.log(`OLTP: ${process.env.OTEL_EXPORTER_OTLP_ENDPOINT}`)
        console.log(`Server listening on port ${port}`)
        logger.emit({ severityText: 'INFO', severityNumber: 9, body: `Server listening on port ${port}` })
        span.addEvent('Server listening on port')
        span.end()
    })
})


