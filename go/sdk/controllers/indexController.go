package controllers

import (
	"context"

	"github.com/gofiber/fiber/v2"

	"go.opentelemetry.io/contrib/bridges/otelslog"
	"go.opentelemetry.io/otel"
)


func Index(c *fiber.Ctx) error {
	name := "otel-examples-go-sdk"

	tracer := otel.Tracer(name)
	logger := otelslog.NewLogger(name)
	// Start a span
	ctx, span := tracer.Start(context.Background(), "index")
	defer span.End()

	logger.InfoContext(ctx, "root path", "message", "indexController")

	return c.JSON(fiber.Map{"msg": "otel-examples-go-sdk"})
}
