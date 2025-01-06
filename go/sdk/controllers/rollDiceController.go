package controllers

import (
	"context"
	"strconv"

	"math/rand"

	"github.com/gofiber/fiber/v2"

	"go.opentelemetry.io/contrib/bridges/otelslog"
	"go.opentelemetry.io/otel"
	"go.opentelemetry.io/otel/attribute"
	"go.opentelemetry.io/otel/metric"
)

var name = "otel-examples-go-sdk"
var rollCnt metric.Int64Counter

func RollDice(c *fiber.Ctx) error {
	name := "otel-examples-go-sdk"

	tracer := otel.Tracer(name)
	meter  := otel.Meter(name)
	logger := otelslog.NewLogger(name)
	// Start a span
	ctx, span := tracer.Start(context.Background(), "roll")
	defer span.End()

	roll := 1 + rand.Intn(6)

	msg := "Anonymous player is rolling the dice"
	logger.InfoContext(ctx, msg, "result", roll)

	rollCnt, err := meter.Int64Counter("dice.rolls",
		metric.WithDescription("The number of rolls by roll value"),
		metric.WithUnit("{roll}"))

	rollValueAttr := attribute.Int("roll.value", roll)
	span.SetAttributes(rollValueAttr)
	rollCnt.Add(ctx, 1, metric.WithAttributes(rollValueAttr))
			
	if err != nil {
		panic(err)
	}
	res := strconv.Itoa(roll) + "\n"

	return c.JSON(fiber.Map{"roll": res})
}