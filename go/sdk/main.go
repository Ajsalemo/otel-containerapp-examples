package main

import (
	"otel-examples-go-sdk/controllers"

	"github.com/gofiber/fiber/v2"
)

func main() {
	app := fiber.New()

	app.Get("/", controllers.Index)
	app.Get("/api/reqheaders", controllers.ReqHeaders)

	app.Listen(":3000")
}