using Microsoft.AspNetCore.Mvc;

namespace sdk.Controllers;

public class IndexController(ILogger<IndexController> logger) : ControllerBase
{
    private ILogger<IndexController> logger = logger;

    [HttpGet("/")]
    public string Get()
    {
        string msg = "otel-examples-dotnet-sdk";
        logger.LogInformation(msg);
        return msg;
    }
}