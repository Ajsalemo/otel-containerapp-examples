# otel-sdk-examples-node-codeless

**Local development**:
1. Build and run the image
2. When running the container, pass the following environments variables to it so otel telemetry logs, traces, metrics, are logged to the console:
- `NODE_OPTIONS`: `--require @opentelemetry/auto-instrumentations-node/register`
- `OTEL_TRACES_EXPORTER`: `console`

**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry
2. Create a Container App with [OpenTelemetry enabled](https://learn.microsoft.com/en-us/azure/container-apps/opentelemetry-agents?tabs=azure-cli#environment-variables) enabled on the environment. Use the newly built image. When running the container, pass the following environments for the container. This can be done on the Container App or hardcoding (not recommended) it somewhere like in the `Dockerfile`:
- `NODE_OPTIONS`: `--require @opentelemetry/auto-instrumentations-node/register`
- `OTEL_TRACES_EXPORTER`: `otlp`

Limitations for the codeless agent are called out [here](https://opentelemetry.io/docs/zero-code/js/#configuring-the-module)