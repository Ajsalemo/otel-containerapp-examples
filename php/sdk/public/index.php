<?php

use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Monolog\Logger;
use OpenTelemetry\API\Globals;
use OpenTelemetry\Contrib\Logs\Monolog\Handler;
use OpenTelemetry\Contrib\Grpc\GrpcTransportFactory;
use OpenTelemetry\Contrib\Otlp\OtlpUtil;
use OpenTelemetry\Contrib\Otlp\LogsExporter;
use OpenTelemetry\API\Common\Time\Clock;
use OpenTelemetry\SDK\Logs\EventLoggerProvider;
use OpenTelemetry\SDK\Logs\LoggerProvider;
use OpenTelemetry\SDK\Logs\LogRecordLimitsBuilder;
use OpenTelemetry\SDK\Logs\Processor\BatchLogRecordProcessor;
use OpenTelemetry\SDK\Common\Instrumentation\InstrumentationScopeFactory;
use Psr\Log\LogLevel;
use OpenTelemetry\API\Signals;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Logs\Processor\SimpleLogRecordProcessor;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Logs\Exporter\ConsoleExporterFactory;
use Illuminate\Support\Facades\Log;
use OpenTelemetry\API\Metrics\ObserverInterface;
use OpenTelemetry\Contrib\Otlp\MetricExporter;
use OpenTelemetry\SDK\Metrics\Data\Temporality;
use OpenTelemetry\SDK\Metrics\MeterProvider;
use OpenTelemetry\SDK\Metrics\MetricExporter\ConsoleMetricExporter;
use OpenTelemetry\SDK\Metrics\MetricReader\ExportingReader;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
>>>>>>> 0c5a96efb50e95a6b41c3f5ac688605121df7479

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
<<<<<<< HEAD
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
=======
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
>>>>>>> 0c5a96efb50e95a6b41c3f5ac688605121df7479
    require $maintenance;
}

// Register the Composer autoloader...
<<<<<<< HEAD
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
=======
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());

>>>>>>> 0c5a96efb50e95a6b41c3f5ac688605121df7479
