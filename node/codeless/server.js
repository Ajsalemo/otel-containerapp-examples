import express from "express";

import { homeController } from "./controllers/homeController.js";
import { reqHeadersController } from "./controllers/reqHeadersController.js";

const port = process.env.PORT || 3000;
const app = express()

app.use(homeController)
app.use("/api/reqheaders", reqHeadersController)

app.listen(port, () => {
    console.log(`OLTP: ${process.env.OTEL_EXPORTER_OTLP_ENDPOINT}`)
    console.log(`Server listening on port ${port}`)
})

