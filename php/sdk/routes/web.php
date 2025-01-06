<?php

<<<<<<< HEAD
use Monolog\Logger;
use Illuminate\Support\Facades\Route;
use OpenTelemetry\API\Globals;
use OpenTelemetry\Contrib\Logs\Monolog\Handler;
use OpenTelemetry\Contrib\Grpc\GrpcTransportFactory;
use OpenTelemetry\Contrib\Otlp\OtlpUtil;
use Psr\Log\LogLevel;

$tracer = Globals::tracerProvider()->getTracer('laravel-tracer');
$loggerProvider = Globals::loggerProvider();
$handler = new Handler(
    $loggerProvider,
    LogLevel::INFO
);
$monolog = new Logger('otel-php-monolog', [$handler]);

Route::get('/', function () use ($monolog) {
    $monolog->info('root path', ['message' => 'root path']);
=======
require __DIR__ . '/../otel/instrumentation.php';

use OpenTelemetry\API\Logs\Severity;
use OpenTelemetry\SDK\Logs\EventLoggerProvider;
use OpenTelemetry\SDK\Metrics\Data\Temporality;
use Illuminate\Support\Facades\Route;
use OtelInstrumentation\Instrumentation;

$instrumentation = new Instrumentation();
$log = $instrumentation->getLogger();
$tracer = $instrumentation->getTracer();
$metrics = $instrumentation->getMetrics();

$log->getLogger('php-otel', '0.1.0');
$eventLoggerProvider = new EventLoggerProvider($log);
$logger = $eventLoggerProvider->getEventLogger(getenv('OTEL_SERVICE_NAME') ?? 'otel-sdk-examples-php-sdk', '0.1.0');

Route::get('/', function () use ($logger, $metrics, $tracer) {
    $logger->emit(
        name: 'root path',
        body: 'root path',
        severityNumber: Severity::INFO
    );

    $trace = $tracer->getTracer(
        getenv('OTEL_SERVICE_NAME') ?? 'otel-sdk-examples-php-sdk', 
        '0.1.0',
    );

    $span = $trace->spanBuilder("root-span")->startSpan();

    $span->setAttribute('root', 'root path');
    $span->addEvent('root', ['root' => 'root path']);


    $httpMeter = $metrics->getMeter('io.opentelemetry.contrib.php')->createHistogram(
        'http.server.duration',
        'ms',
        'measures the duration inbound HTTP requests',
    );
    
    $httpMeter->record(100, ['path' => '/']);
    // During the time range (T0, T1]:
    $httpMeter->record(50, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(1, ['http.method' => 'GET', 'http.status_code' => 500]);

    // During the time range (T2, T3]:
    $httpMeter->record(5, ['http.method' => 'GET', 'http.status_code' => 500]);
    $httpMeter->record(2, ['http.method' => 'GET', 'http.status_code' => 500]);

    // During the time range (T3, T4]:
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);

    // During the time range (T4, T5]:
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(30, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(50, ['http.method' => 'GET', 'http.status_code' => 200]);

    $metrics->forceFlush();
    $span->end();   
>>>>>>> 0c5a96efb50e95a6b41c3f5ac688605121df7479

    return view('welcome');
});

<<<<<<< HEAD
Route::get('/api/reqheaders', function () use ($monolog) {
    $headers = request()->headers->all();

    $monolog->info('request headers', ['result' => $headers]);
    return response()->json([
        'headers' => $headers
    ]); 
});

Route::get('/rolldice', function () use ($tracer, $monolog) {
    $span = $tracer
        ->spanBuilder('manual-span')
        ->startSpan();
    $result = random_int(1,6);

    $span
        ->addEvent('rolled dice', ['result' => $result])
        ->end();

    $monolog->info('dice rolled', ['result' => $result]);

    return response()->json([
        'roll' => $result
    ]); 
=======
Route::get('/api/reqheaders', function () use ($logger, $metrics, $tracer) {
    $headers = request()->headers->all();
    $trace = $tracer->getTracer(
        getenv('OTEL_SERVICE_NAME') ?? 'otel-sdk-examples-php-sdk', 
        '0.1.0',
    );

    $span = $trace->spanBuilder("request-header-span")->startSpan();

    $span->setAttribute('request_headers', $headers);
    $span->addEvent('request_headers', ['request_headers' => $headers]);

    // If APP_ENV is set to 'dev', use Monolog to log the message
    // Otherwise, use OpenTelemetry to emit the log
    $logger->emit(
        name: 'request headers',
        body: $headers,
        severityNumber: Severity::INFO
    );

    $httpMeter = $metrics->getMeter('io.opentelemetry.contrib.php')->createHistogram(
        'http.server.duration',
        'ms',
        'measures the duration inbound HTTP requests',
    );
    
    $httpMeter->record(100, ['path' => '/']);
    // During the time range (T0, T1]:
    $httpMeter->record(50, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(1, ['http.method' => 'GET', 'http.status_code' => 500]);

    // During the time range (T2, T3]:
    $httpMeter->record(5, ['http.method' => 'GET', 'http.status_code' => 500]);
    $httpMeter->record(2, ['http.method' => 'GET', 'http.status_code' => 500]);

    // During the time range (T3, T4]:
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);

    // During the time range (T4, T5]:
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(30, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(50, ['http.method' => 'GET', 'http.status_code' => 200]);

    $metrics->forceFlush();
    $span->end();   

    return response()->json([
        'headers' => $headers
    ]);
});

Route::get('/rolldice', function () use ($tracer, $logger, $metrics) {
    $trace = $tracer->getTracer(
        getenv('OTEL_SERVICE_NAME') ?? 'otel-sdk-examples-php-sdk', 
        '0.1.0',
    );

    $span = $trace->spanBuilder("dice-span")->startSpan();
    $result = random_int(1, 6);

    $span->setAttribute('dice roll', $result);
    $span->addEvent('dice rolled', ['roll' => $result]);

    $logger->emit(
        name: 'dice rolled',
        body: $result,
        severityNumber: Severity::INFO
    );

    $httpMeter = $metrics->getMeter('io.opentelemetry.contrib.php')->createHistogram(
        'http.server.duration',
        'ms',
        'measures the duration inbound HTTP requests',
    );
    
    $httpMeter->record(100, ['path' => '/']);
    // During the time range (T0, T1]:
    $httpMeter->record(50, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(1, ['http.method' => 'GET', 'http.status_code' => 500]);

    // During the time range (T2, T3]:
    $httpMeter->record(5, ['http.method' => 'GET', 'http.status_code' => 500]);
    $httpMeter->record(2, ['http.method' => 'GET', 'http.status_code' => 500]);

    // During the time range (T3, T4]:
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);

    // During the time range (T4, T5]:
    $httpMeter->record(100, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(30, ['http.method' => 'GET', 'http.status_code' => 200]);
    $httpMeter->record(50, ['http.method' => 'GET', 'http.status_code' => 200]);

    $metrics->forceFlush();
    
    $span->end();
    return response()->json([
        'roll' => $result
    ]);
>>>>>>> 0c5a96efb50e95a6b41c3f5ac688605121df7479
});
