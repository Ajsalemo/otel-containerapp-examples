export OTEL_PYTHON_LOGGING_AUTO_INSTRUMENTATION_ENABLED=true

# Use the below locally or in development. This writes to the console only
export OTEL_TRACES_EXPORTER=console
export OTEL_METRICS_EXPORTER=console
export OTEL_LOGS_EXPORTER=console
export OTEL_PYTHON_LOG_LEVEL=info
export OTEL_SERVICE_NAME="otel-sdk-examples-python-codeless"

# Use the below in a production environment
export OTEL_TRACES_EXPORTER=otlp
export OTEL_METRICS_EXPORTER=otlp
export OTEL_LOGS_EXPORTER=otlp
