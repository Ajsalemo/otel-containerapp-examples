package controllers

import (
	"log"
	"strconv"

	"github.com/gofiber/fiber/v2"
	"math/rand"
)

func RollDice(c *fiber.Ctx) error {
	roll := 1 + rand.Intn(6)
	res := strconv.Itoa(roll) + "\n"
	
	log.Default().Println(res)
	return c.JSON(fiber.Map{"roll": res})
}