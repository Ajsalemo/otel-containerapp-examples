using Microsoft.AspNetCore.Mvc;

namespace sdk.Controllers;

public class IndexController : ControllerBase
{
    [HttpGet("/")]
    public string Get()
    {
        string msg = "otel-examples-dotnet-sdk";
        return msg;
    }
}