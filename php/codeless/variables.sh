export OTEL_PHP_AUTOLOAD_ENABLED=true
export OTEL_SERVICE_NAME="otel-examples-php-codeless"
export OTEL_TRACES_EXPORTER=console
export OTEL_LOGS_EXPORTER=console
export OTEL_METRICS_EXPORTER=console
export OTEL_EXPORTER_OTLP_PROTOCOL=grpc
export OTEL_EXPORTER_OTLP_ENDPOINT="http://collector:4317"
export OTEL_PROPAGATORS=baggage,tracecontext

