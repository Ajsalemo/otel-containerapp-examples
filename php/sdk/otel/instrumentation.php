<?php

namespace OtelInstrumentation;

use OpenTelemetry\Contrib\Grpc\GrpcTransportFactory;
use OpenTelemetry\Contrib\Otlp\OtlpUtil;
use OpenTelemetry\Contrib\Otlp\LogsExporter;
use OpenTelemetry\SDK\Logs\LoggerProvider;
use OpenTelemetry\API\Signals;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Logs\Processor\SimpleLogRecordProcessor;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Logs\Exporter\ConsoleExporterFactory;
use Illuminate\Support\Facades\Log;
use OpenTelemetry\Contrib\Otlp\MetricExporter;
use OpenTelemetry\SDK\Metrics\Data\Temporality;
use OpenTelemetry\SDK\Metrics\MeterProvider;
use OpenTelemetry\SDK\Metrics\MetricReader\ExportingReader;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
use OpenTelemetry\SDK\Common\Export\Stream\StreamTransportFactory;
use OpenTelemetry\SemConv\ResourceAttributes;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Sdk;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\Sampler\ParentBased;


class Instrumentation
{
    public $tracer;
    public $logger;
    public $metrics;
    public $resource;
    public $otelEndpoint;

    public function __construct()
    {
        $this->otelEndpoint = isset($_ENV['OTEL_EXPORTER_OTLP_ENDPOINT']) ? $_ENV['OTEL_EXPORTER_OTLP_ENDPOINT'] : 'http://localhost:4317';
        $this->resource = ResourceInfoFactory::emptyResource()->merge(ResourceInfo::create(Attributes::create([
            ResourceAttributes::SERVICE_NAMESPACE => 'otel',
            ResourceAttributes::SERVICE_NAME => getenv('OTEL_SERVICE_NAME') ?? 'otel-sdk-examples-php-sdk',
            ResourceAttributes::SERVICE_VERSION => '0.1.0',
        ])));
        $this->setTracer();
        $this->setLogger();
        $this->setMetrics();
        $this->registerSdk();
    }

    public function setMetrics()
    {
        // Set up metrics
        if (getenv('APP_ENV') == 'dev') {
            $reader = new ExportingReader(new MetricExporter((new StreamTransportFactory())->create(fopen('php://stdout', 'wb'), 'application/x-ndjson'), /*Temporality::CUMULATIVE*/));

            $meterProvider = MeterProvider::builder()
                ->addReader($reader)
                ->build();

            return $this->metrics = $meterProvider;
        } else {
            $transport = (new GrpcTransportFactory())->create($this->otelEndpoint . OtlpUtil::method(Signals::METRICS));
            $reader = new ExportingReader(new MetricExporter($transport, Temporality::CUMULATIVE));

            $meterProvider = MeterProvider::builder()
                ->setResource($this->resource)
                ->addReader($reader)
                ->build();

            return $this->metrics = $meterProvider;
        }
    }

    public function setTracer()
    {
        if (getenv('APP_ENV') == 'dev') {
            $tracer = new SpanExporter((new StreamTransportFactory())->create(fopen('php://stdout', 'wb'), 'application/x-ndjson'));

            $tracerProvider = TracerProvider::builder()
                ->addSpanProcessor(
                    new SimpleSpanProcessor($tracer)
                )
                ->setResource($this->resource)
                ->setSampler(new ParentBased(new AlwaysOnSampler()))
                ->build();

            return $this->tracer = $tracerProvider;
        } else {
            $transport = (new GrpcTransportFactory())->create($this->otelEndpoint . OtlpUtil::method(Signals::TRACE));
            $spanExporter = new SpanExporter($transport);

            $tracerProvider = TracerProvider::builder()
                ->addSpanProcessor(
                    new SimpleSpanProcessor($spanExporter)
                )
                ->setResource($this->resource)
                ->setSampler(new ParentBased(new AlwaysOnSampler()))
                ->build();

            return $this->tracer = $tracerProvider;
        }
    }

    public function setLogger()
    {
        // Set up logging
        if (getenv('APP_ENV') == 'dev') {
            Log::channel('stdout')->info(message: 'OTEL endpoint: ' . $this->otelEndpoint);

            $loggerProvider = LoggerProvider::builder()
                ->setResource($this->resource)
                ->addLogRecordProcessor(
                    new SimpleLogRecordProcessor(
                        (new ConsoleExporterFactory())->create()
                    )
                )
                ->build();

            return $this->logger = $loggerProvider;
        } else {
            Log::channel('stdout')->info(message: 'OTEL endpoint: ' . $this->otelEndpoint);

            $transport = (new GrpcTransportFactory())->create($this->otelEndpoint . OtlpUtil::method(Signals::LOGS));
            $logExporter = new LogsExporter(
                $transport
            );

            // Create LoggerProvider for logs
            $loggerProvider = LoggerProvider::builder()
                ->setResource($this->resource)
                ->addLogRecordProcessor(
                    new SimpleLogRecordProcessor($logExporter)
                )
                ->build();

            return $this->logger = $loggerProvider;
        }
    }

    public function registerSdk()
    {
        // Build and register the global SDK
        Sdk::builder()
            ->setTracerProvider($this->tracer)
            ->setMeterProvider($this->metrics)
            ->setLoggerProvider($this->logger)
            ->setPropagator(TraceContextPropagator::getInstance())
            ->setAutoShutdown(true)
            ->buildAndRegisterGlobal();
    }

    public function getTracer()
    {
        return $this->tracer;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getMetrics()
    {
        return $this->metrics;
    }
}
