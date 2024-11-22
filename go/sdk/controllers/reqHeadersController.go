package controllers

import (
	"log"

	"github.com/gofiber/fiber/v2"
)

// ReqHeaders function
func ReqHeaders(c *fiber.Ctx) error {
	log.Default().Println(c.GetReqHeaders())
	return c.JSON(fiber.Map{"headers": c.GetReqHeaders()})
}