using System.Globalization;
using Microsoft.AspNetCore.Mvc;

namespace codeless.Controllers;

public class RollDiceController(ILogger<RollDiceController> logger) : ControllerBase
{
    private ILogger<RollDiceController> logger = logger;

    [HttpGet("/rolldice")]
    public string Get()
    {
        static int RollDice()
        {
            return Random.Shared.Next(1, 7);
        }

        string HandleRollDice(string? player)
        {
            var result = RollDice();

            if (string.IsNullOrEmpty(player))
            {
                logger.LogInformation("Anonymous player is rolling the dice: {result}", result);
            }
            else
            {
                logger.LogInformation("{player} is rolling the dice: {result}", player, result);
            }

            return result.ToString(CultureInfo.InvariantCulture);
        }

        return HandleRollDice(null);
    }
}