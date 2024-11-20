# Use this for local development when not running in a container - for deployed environments, set these values to `otlp`
export JAVA_TOOL_OPTIONS="-javaagent:./otel/opentelemetry-javaagent.jar" \
  OTEL_TRACES_EXPORTER=logging \
  OTEL_METRICS_EXPORTER=logging \
  OTEL_LOGS_EXPORTER=logging \
  OTEL_METRIC_EXPORT_INTERVAL=15000

# Use this for when running in a container
# Use this for local development when not running in a container - for deployed environments, set these values to `otlp`
export JAVA_TOOL_OPTIONS="-javaagent:/usr/src/app/otel/opentelemetry-javaagent.jar" \
  OTEL_TRACES_EXPORTER=otlp \
  OTEL_METRICS_EXPORTER=otlp \
  OTEL_LOGS_EXPORTER=otlp \
  OTEL_METRIC_EXPORT_INTERVAL=15000

