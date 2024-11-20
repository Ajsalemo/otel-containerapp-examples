<?php

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

    return view('welcome');
});

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
});
