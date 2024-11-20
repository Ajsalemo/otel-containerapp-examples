# otel-sdk-examples-java-sdk

Note, this "agent" is essentially a form of "codeless instrumentation", since the only thing required is the opentelemetry `.jar` and environment variables at a minimum - [Instrumentation ecosystem](https://opentelemetry.io/docs/languages/java/instrumentation/)

**Local development**:
1. Build and run the image
2. When running the container, pass the following environments variables to it so otel telemetry logs, traces, metrics, are logged to the console, or, run `source variables.sh`:

```
export JAVA_TOOL_OPTIONS="-javaagent:./otel/opentelemetry-javaagent.jar" \
  OTEL_TRACES_EXPORTER=logging \
  OTEL_METRICS_EXPORTER=logging \
  OTEL_LOGS_EXPORTER=logging \
  OTEL_METRIC_EXPORT_INTERVAL=15000
```



**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry. 
2. Create a Container App with [OpenTelemetry enabled](https://learn.microsoft.com/en-us/azure/container-apps/opentelemetry-agents?tabs=azure-cli#environment-variables) enabled on the environment - use this newly built image and add the following environment variables:
- `JAVA_TOOL_OPTIONS="-javaagent:./otel/opentelemetry-javaagent.jar"`
- `OTEL_TRACES_EXPORTER=otlp`
- `OTEL_METRICS_EXPORTER=otlp`
- `OTEL_LOGS_EXPORTER=otlp`
- `OTEL_METRIC_EXPORT_INTERVAL=15000`