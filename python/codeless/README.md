# otel-sdk-examples-python-codeless
This application is configured to use ['zero code instrumentation'](https://opentelemetry.io/docs/zero-code/python/configuration/)

**Local development**:
1. Build and run the image
2. When running the container, pass the following environments variables to it so otel telemetry logs, traces, metrics, are logged to the console:
    - `OTEL_TRACES_EXPORTER=console`
    - `OTEL_METRICS_EXPORTER=console`
    - `OTEL_LOGS_EXPORTER=console`
    - `OTEL_PYTHON_LOG_LEVEL=info`
    - `OTEL_SERVICE_NAME="otel-sdk-examples-python-codeless"`
    - `OTEL_PYTHON_LOGGING_AUTO_INSTRUMENTATION_ENABLED=true`
    
**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry
2. When running the container, pass the following environments variables to it so otel telemetry are passed to your configured backend:
    - `OTEL_TRACES_EXPORTER=otlp`
    - `OTEL_METRICS_EXPORTER=otlp`
    - `OTEL_LOGS_EXPORTER=otlp`
    - `OTEL_PYTHON_LOGGING_AUTO_INSTRUMENTATION_ENABLED=true`