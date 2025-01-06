import logging
import os

from opentelemetry import metrics, trace
from opentelemetry._logs import set_logger_provider
from opentelemetry.exporter.otlp.proto.grpc._log_exporter import \
    OTLPLogExporter
from opentelemetry.exporter.otlp.proto.grpc.metric_exporter import \
    OTLPMetricExporter
from opentelemetry.exporter.otlp.proto.grpc.trace_exporter import \
    OTLPSpanExporter
from opentelemetry.sdk._logs import LoggerProvider, LoggingHandler
from opentelemetry.sdk._logs.export import (BatchLogRecordProcessor,
                                            ConsoleLogExporter)
from opentelemetry.sdk.metrics import MeterProvider
from opentelemetry.sdk.metrics.export import (ConsoleMetricExporter,
                                              PeriodicExportingMetricReader)
from opentelemetry.sdk.resources import SERVICE_NAME, Resource
from opentelemetry.sdk.trace import TracerProvider
from opentelemetry.sdk.trace.export import (BatchSpanProcessor,
                                            ConsoleSpanExporter)


def initialize_instrumentation():
    # Service name is required for most backends
    resource = Resource(attributes={
        SERVICE_NAME: os.getenv('OTEL_SERVICE_NAME',
                                'otel-sdk-examples-python-sdk')
    })

    traceProvider = TracerProvider(resource=resource)

    if os.getenv('ENVIRONMENT') == 'dev':
        processor = BatchSpanProcessor(ConsoleSpanExporter())
    else:
        processor = BatchSpanProcessor(OTLPSpanExporter(
            endpoint=os.getenv('OTEL_EXPORTER_OTLP_ENDPOINT', 'localhost:4317')))

    traceProvider.add_span_processor(processor)
    trace.set_tracer_provider(traceProvider)

    if os.getenv('ENVIRONMENT') == 'dev':
        reader = PeriodicExportingMetricReader(ConsoleMetricExporter())
    else:
        reader = PeriodicExportingMetricReader(
            OTLPMetricExporter(endpoint=os.getenv(
                'OTEL_EXPORTER_OTLP_ENDPOINT', 'localhost:4317'))
        )

    meterProvider = MeterProvider(resource=resource, metric_readers=[reader])
    metrics.set_meter_provider(meterProvider)

def initialize_otel_logging():
    logger_provider = LoggerProvider(
        resource=Resource.create(
            {
                "service.name": os.getenv('OTEL_SERVICE_NAME',
                                'otel-sdk-examples-python-sdk'),
            }
        ),
    )
    set_logger_provider(logger_provider)

    if os.getenv('ENVIRONMENT') == 'dev':
        exporter = ConsoleLogExporter()
    else:
        exporter = OTLPLogExporter(insecure=True)

    logger_provider.add_log_record_processor(BatchLogRecordProcessor(exporter))
    handler = LoggingHandler(level=logging.DEBUG, logger_provider=logger_provider)

    # Attach OTLP handler to root logger
    logging.getLogger().addHandler(handler)
    logging.getLogger().addHandler(logging.StreamHandler())
    logging.basicConfig(level=logging.INFO)

    logging.getLogger('instrumentation').setLevel(logging.DEBUG)
    app_logger = logging.getLogger('instrumentation')
    app_logger.info("Logging initialized")

    return app_logger

