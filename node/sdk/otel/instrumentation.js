import { diag, DiagConsoleLogger, DiagLogLevel } from '@opentelemetry/api';
import { getNodeAutoInstrumentations } from '@opentelemetry/auto-instrumentations-node';
import { OTLPMetricExporter } from '@opentelemetry/exporter-metrics-otlp-grpc';
import { OTLPTraceExporter } from '@opentelemetry/exporter-trace-otlp-grpc';
import { ConsoleMetricExporter, PeriodicExportingMetricReader } from '@opentelemetry/sdk-metrics';
import { NodeSDK } from '@opentelemetry/sdk-node';
import { ConsoleSpanExporter } from '@opentelemetry/sdk-trace-node';

// For troubleshooting, set the log level to DiagLogLevel.DEBUG
diag.setLogger(new DiagConsoleLogger(), DiagLogLevel.INFO);

const sdk = new NodeSDK({
    traceExporter: process.env.NODE_ENV === "dev" ? new ConsoleSpanExporter() : new OTLPTraceExporter({
        url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT,
        headers: {
            exporterType: 'trace',
        }
    }),
    metricReader: new PeriodicExportingMetricReader({
        exporter: process.env.NODE_ENV === "dev" ? new ConsoleMetricExporter() : new OTLPMetricExporter({
            url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT,
            headers: {
                exporterType: 'metric',
            }
        }),
    }),
    instrumentations: [getNodeAutoInstrumentations()],
});

sdk.start();