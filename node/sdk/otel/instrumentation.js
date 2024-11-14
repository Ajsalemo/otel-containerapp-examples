import { diag, DiagConsoleLogger, DiagLogLevel } from '@opentelemetry/api';
import { getNodeAutoInstrumentations } from '@opentelemetry/auto-instrumentations-node';
import { OTLPLogExporter } from '@opentelemetry/exporter-logs-otlp-grpc';
import { OTLPMetricExporter } from '@opentelemetry/exporter-metrics-otlp-grpc';
import { OTLPTraceExporter } from '@opentelemetry/exporter-trace-otlp-grpc';
import { BatchLogRecordProcessor, ConsoleLogRecordExporter, LoggerProvider } from '@opentelemetry/sdk-logs';
import { ConsoleMetricExporter, PeriodicExportingMetricReader } from '@opentelemetry/sdk-metrics';
import { NodeSDK } from '@opentelemetry/sdk-node';
import { BatchSpanProcessor, ConsoleSpanExporter } from '@opentelemetry/sdk-trace-node';
// For troubleshooting, set the log level to DiagLogLevel.DEBUG
diag.setLogger(new DiagConsoleLogger(), DiagLogLevel.INFO);
// Start setting up the tracer for spans
// Note: In App Insights this does not actually equate to 'trace' - the logger below does
const exporter = process.env.NODE_ENV === 'dev' ? new ConsoleSpanExporter() : new OTLPTraceExporter({
    url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT,
});
// Set up metrics
const metricReader = new PeriodicExportingMetricReader({
    exporter: process.env.NODE_ENV === 'dev' ? new ConsoleMetricExporter() : new OTLPMetricExporter({
        url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT,
    }),
})
// Set up logger usage to be passed around
const loggerExporter = process.env.NODE_ENV === 'dev' ? new BatchLogRecordProcessor(new ConsoleLogRecordExporter()) : new BatchLogRecordProcessor(new OTLPLogExporter({
    url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT
}));

const loggerProvider = new LoggerProvider();
loggerProvider.addLogRecordProcessor(loggerExporter);

export const logger = loggerProvider.getLogger('default');

const sdk = new NodeSDK({
    traceExporter: exporter,
    metricReader: metricReader,
    logRecordProcessors: [loggerProvider],
    spanProcessors: [new BatchSpanProcessor(exporter)],
    instrumentations: [getNodeAutoInstrumentations()],
});

logger.emit({ severityText: 'INFO', severityNumber: 9, body: 'instrumentation complete, starting SDK' });
sdk.start();
