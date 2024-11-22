# otel-sdk-examples-python-codeless
This application is configured to use ['zero code instrumentation'](https://opentelemetry.io/docs/zero-code/python/configuration/)

**Local development**:
1. Build and run the image
2. When running the container, pass the following environments variables to it so otel telemetry logs, traces, metrics, are logged to the console:
    - `OTEL_TRACES_EXPORTER=console`
    - `OTEL_METRICS_EXPORTER=console`
    - `OTEL_LOGS_EXPORTER=console`
    - `OTEL_SERVICE_NAME="otel-sdk-examples-python-codeless"`
    
**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry
2. Create a Container App with [OpenTelemetry enabled](https://learn.microsoft.com/en-us/azure/container-apps/opentelemetry-agents?tabs=azure-cli#environment-variables) enabled on the environment. Use the newly built image. When running the container, pass the following environments variables to it so otel telemetry are passed to your configured backend:
    - `OTEL_TRACES_EXPORTER=otlp`
    - `OTEL_METRICS_EXPORTER=otlp`
    - `OTEL_LOGS_EXPORTER=otlp`
