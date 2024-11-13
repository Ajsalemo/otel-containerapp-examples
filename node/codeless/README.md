# node

Set the following environments for the container. This can be done on the Container App (or not recommended), hardcoding it somewhere like in the `Dockerfile`:
- `NODE_OPTIONS`: `--require @opentelemetry/auto-instrumentations-node/register`
- `OTEL_TRACES_EXPORTER`: `otlp`