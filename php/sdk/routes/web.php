<?php

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

    return view('welcome');
});

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
});
