package controllers

import (
	"context"

	"github.com/gofiber/fiber/v2"
	"go.opentelemetry.io/contrib/bridges/otelslog"
	"go.opentelemetry.io/otel"
)

// ReqHeaders function
func ReqHeaders(c *fiber.Ctx) error {
	tracer := otel.Tracer(name)
	logger := otelslog.NewLogger(name)
	// Start a span
	ctx, span := tracer.Start(context.Background(), "reqHeaders")
	defer span.End()

	logger.InfoContext(ctx, "logging request headers", "headers", c.GetReqHeaders())

	return c.JSON(fiber.Map{"headers": c.GetReqHeaders()})
}