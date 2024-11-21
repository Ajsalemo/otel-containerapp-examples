using System.Globalization;
using Microsoft.AspNetCore.Mvc;

namespace sdk.Controllers;

public class RollDiceController : ControllerBase
{
    [HttpGet("/rolldice")]
    public string Get()
    {
        using ILoggerFactory factory = LoggerFactory.Create(builder => builder.AddConsole());
        ILogger logger = factory.CreateLogger<Program>();

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