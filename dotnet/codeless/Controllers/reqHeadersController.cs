using System.Text.Json;
using Microsoft.AspNetCore.Mvc;

namespace codeless.Controllers;

public class ReqHeadersController(ILogger<ReqHeadersController> logger) : ControllerBase
{
    private ILogger<ReqHeadersController> logger = logger;

    [HttpGet("/api/reqheaders")]
    public string Get()
    {
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