package controllers

import "github.com/gofiber/fiber/v2"

func Index(c *fiber.Ctx) error {
	return c.JSON(fiber.Map{"msg": "otel-examples-go-sdk"})
}
