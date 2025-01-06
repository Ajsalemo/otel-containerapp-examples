package main

import (
	"context"
	"errors"
	"os"
	"os/signal"
	"otel-examples-go-sdk/controllers"
	"syscall"

	otel "otel-examples-go-sdk/otel"

	"go.opentelemetry.io/contrib/bridges/otelslog"

	"github.com/gofiber/fiber/v2"
)

func main() {
	name := "otel-examples-go-sdk"
	logger := otelslog.NewLogger(name)

	// Handle SIGINT (CTRL+C) gracefully.
	ctx, stop := signal.NotifyContext(context.Background(), os.Interrupt)
	defer stop()

	// Set up OpenTelemetry.
	otelShutdown, err := otel.SetupOTelSDK(ctx)
	if err != nil {
		return
	}
	// Handle shutdown properly so nothing leaks.
	defer func() {
		err = errors.Join(err, otelShutdown(context.Background()))
	}()

	app := fiber.New()

	app.Get("/", controllers.Index)
	app.Get("/api/reqheaders", controllers.ReqHeaders)
	app.Get("/rolldice", controllers.RollDice)

	// Notify the application of the below signals to be handled on shutdown
	s := make(chan os.Signal, 1)
	signal.Notify(s,
		syscall.SIGINT,
		syscall.SIGTERM,
		syscall.SIGQUIT)
	// Goroutine to clean up prior to shutting down
	go func() {
		sig := <-s
		switch sig {
		case os.Interrupt:
			logger.InfoContext(ctx, "CTRL+C / os.Interrupt recieved, shutting down the application..")
			stop()
			app.Shutdown()
		case syscall.SIGTERM:
			logger.InfoContext(ctx, "SIGTERM recieved.., shutting down the application..")
			stop()
			app.Shutdown()
		case syscall.SIGQUIT:
			logger.InfoContext(ctx, "SIGQUIT recieved.., shutting down the application..")
			stop()
			app.Shutdown()
		case syscall.SIGINT:
			logger.InfoContext(ctx, "SIGINT recieved.., shutting down the application..")
			stop()
			app.Shutdown()
		}
	}()

	logger.InfoContext(ctx, "Listening on :3000")
	app.Listen(":3000")
}
