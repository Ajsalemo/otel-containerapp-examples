package otel

import (
	"context"
	"errors"
	"os"
	"time"
	"fmt"

	"go.opentelemetry.io/otel"
	"go.opentelemetry.io/otel/exporters/otlp/otlplog/otlploggrpc"
	"go.opentelemetry.io/otel/exporters/otlp/otlpmetric/otlpmetricgrpc"
	"go.opentelemetry.io/otel/exporters/otlp/otlptrace/otlptracegrpc"
	"go.opentelemetry.io/otel/exporters/stdout/stdoutlog"
	"go.opentelemetry.io/otel/exporters/stdout/stdoutmetric"
	"go.opentelemetry.io/otel/exporters/stdout/stdouttrace"
	"go.opentelemetry.io/otel/log/global"
	"go.opentelemetry.io/otel/propagation"
	"go.opentelemetry.io/otel/sdk/log"
	"go.opentelemetry.io/otel/sdk/metric"
	"go.opentelemetry.io/otel/sdk/trace"
)

// setupOTelSDK bootstraps the OpenTelemetry pipeline.
// If it does not return an error, make sure to call shutdown for proper cleanup.
func SetupOTelSDK(ctx context.Context) (shutdown func(context.Context) error, err error) {
	var shutdownFuncs []func(context.Context) error

	// shutdown calls cleanup functions registered via shutdownFuncs.
	// The errors from the calls are joined.
	// Each registered cleanup will be invoked once.
	shutdown = func(ctx context.Context) error {
		var err error
		for _, fn := range shutdownFuncs {
			err = errors.Join(err, fn(ctx))
		}
		shutdownFuncs = nil
		return err
	}

	// handleErr calls shutdown for cleanup and makes sure that all errors are returned.
	handleErr := func(inErr error) {
		err = errors.Join(inErr, shutdown(ctx))
	}

	// Set up propagator.
	prop := newPropagator()
	otel.SetTextMapPropagator(prop)

	// Set up trace provider.
	tracerProvider, err := newTraceProvider(context.Background())
	if err != nil {
		handleErr(err)
		return
	}
	shutdownFuncs = append(shutdownFuncs, tracerProvider.Shutdown)
	otel.SetTracerProvider(tracerProvider)

	// Set up meter provider.
	meterProvider, err := newMeterProvider(context.Background())
	if err != nil {
		handleErr(err)
		return
	}
	shutdownFuncs = append(shutdownFuncs, meterProvider.Shutdown)
	otel.SetMeterProvider(meterProvider)

	// Set up logger provider.
	loggerProvider, err := newLoggerProvider(context.Background())
	if err != nil {
		handleErr(err)
		return
	}
	shutdownFuncs = append(shutdownFuncs, loggerProvider.Shutdown)
	global.SetLoggerProvider(loggerProvider)

	return
}

func newPropagator() propagation.TextMapPropagator {
	return propagation.NewCompositeTextMapPropagator(
		propagation.TraceContext{},
		propagation.Baggage{},
	)
}
// Trace provider
func newTraceProvider(ctx context.Context) (*trace.TracerProvider, error) {
	// Use stdout exporter in development.
	if os.Getenv("ENVIRONMENT") == "dev" {
		traceExporter, err := stdouttrace.New(
			stdouttrace.WithPrettyPrint())

		if err != nil {
			return nil, err
		}

		traceProvider := trace.NewTracerProvider(
			trace.WithBatcher(traceExporter,
				// Default is 5s. Set to 1s for demonstrative purposes.
				trace.WithBatchTimeout(time.Second)),
		)

		fmt.Println("Using console trace exporter")
		return traceProvider, nil
	}
	// Otherwise use otlp exporter
	traceExporter, err := otlptracegrpc.New(ctx)

	if err != nil {
		return nil, err
	}

	traceProvider := trace.NewTracerProvider(
		trace.WithBatcher(traceExporter,
			// Default is 5s. Set to 1s for demonstrative purposes.
			trace.WithBatchTimeout(time.Second)),
	)

	fmt.Println("Using otlp trace exporter")
	return traceProvider, nil
}
// Metric provider
func newMeterProvider(ctx context.Context) (*metric.MeterProvider, error) {
	// Use stdout exporter in development
	if os.Getenv("ENVIRONMENT") == "dev" {
		metricExporter, err := stdoutmetric.New()
		if err != nil {
			return nil, err
		}

		meterProvider := metric.NewMeterProvider(
			metric.WithReader(metric.NewPeriodicReader(metricExporter,
				// Default is 1m. Set to 3s for demonstrative purposes.
				metric.WithInterval(3*time.Second))),
		)

		fmt.Println("Using console metric exporter")
		return meterProvider, nil
	}
	// Otherwise use otlp exporter
	metricExporter, err := otlpmetricgrpc.New(ctx)
	if err != nil {
		return nil, err
	}

	meterProvider := metric.NewMeterProvider(
		metric.WithReader(metric.NewPeriodicReader(metricExporter,
			// Default is 1m. Set to 3s for demonstrative purposes.
			metric.WithInterval(3*time.Second))),
	)

	fmt.Println("Using otlp metric exporter")
	return meterProvider, nil
}
// Logger provider
func newLoggerProvider(ctx context.Context) (*log.LoggerProvider, error) {
	// Use stdout exporter in development
	if os.Getenv("ENVIRONMENT") == "dev" {
		logExporter, err := stdoutlog.New()
		if err != nil {
			return nil, err
		}

		loggerProvider := log.NewLoggerProvider(
			log.WithProcessor(log.NewBatchProcessor(logExporter)),
		)

		fmt.Println("Using console log exporter")
		return loggerProvider, nil
	}

	// Otherwise use otlp exporter
	logExporter, err := otlploggrpc.New(ctx)
	if err != nil {
		return nil, err
	}

	loggerProvider := log.NewLoggerProvider(
		log.WithProcessor(log.NewBatchProcessor(logExporter)),
	)

	fmt.Println("Using otlp log exporter")
	return loggerProvider, nil

}
