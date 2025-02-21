# otel-sdk-examples-php-sdk
This application is configured to use ['zero code instrumentation'](https://opentelemetry.io/docs/zero-code/python/configuration/)

**Local development**:
1. Build and run the image
2. When running the container, pass the following environments variables to it so otel telemetry logs, traces, metrics, are logged to the console:
    - `APP_ENV=dev`

**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry
2. Create a Container App with [OpenTelemetry enabled](https://learn.microsoft.com/en-us/azure/container-apps/opentelemetry-agents?tabs=azure-cli#environment-variables) enabled on the environment. Use the newly built image. Note, don't set `APP_ENV=dev` so telemetry is exported to the `otlp` endpoint
