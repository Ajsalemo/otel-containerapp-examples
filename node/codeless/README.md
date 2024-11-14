# node

Set the following environments for the container. This can be done on the Container App or hardcoding (not recommended) it somewhere like in the `Dockerfile`:
- `NODE_OPTIONS`: `--require @opentelemetry/auto-instrumentations-node/register`
- `OTEL_TRACES_EXPORTER`: `otlp`

Limitations for the codeless agent are called out [here](https://opentelemetry.io/docs/zero-code/js/#configuring-the-module)