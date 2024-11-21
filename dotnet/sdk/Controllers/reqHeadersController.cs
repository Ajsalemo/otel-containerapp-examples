using System.Text.Json;
using Microsoft.AspNetCore.Mvc;

namespace sdk.Controllers;

public class ReqHeadersController : ControllerBase
{
    [HttpGet("/api/reqheaders")]
    public string Get()
    {
        using ILoggerFactory factory = LoggerFactory.Create(builder => builder.AddConsole());
        ILogger logger = factory.CreateLogger<Program>();
        var reqDictionary = new Dictionary<string, string>();

        foreach (var header in Request.Headers)
        {
            reqDictionary.Add(header.Key, header.Value.ToString() ?? "");
            logger.LogInformation("{headerKey}: {headerValue}", header.Key, header.Value);
        }

        var jsonReqDictionary = JsonSerializer.Serialize(reqDictionary);
        return jsonReqDictionary;
    }
}