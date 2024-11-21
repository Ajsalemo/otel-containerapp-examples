using Microsoft.AspNetCore.Mvc;

namespace codeless.Controllers;

public class IndexController(ILogger<IndexController> logger) : ControllerBase
{
    private ILogger<IndexController> logger = logger;

    [HttpGet("/")]
    public string Get()
    {
        string msg = "otel-examples-dotnet-codeless";
        logger.LogInformation(msg);
        return msg;
    }
}