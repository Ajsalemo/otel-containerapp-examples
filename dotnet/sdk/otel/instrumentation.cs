using System.Diagnostics;
using System.Diagnostics.Metrics;

namespace sdk.Otel;
public class Instrumentation : IDisposable
{
    internal string ActivitySourceName = System.Environment.GetEnvironmentVariable("OTEL_SERVICE_NAME") ?? "otel-examples-dotnet-sdk";
    internal string MeterName = System.Environment.GetEnvironmentVariable("OTEL_SERVICE_NAME") ?? "otel-examples-dotnet-sdk";
    internal const string ActivitySourceVersion = "1.0.0";
    private readonly Meter meter;

    public Instrumentation()
    {
        string? version = typeof(Instrumentation).Assembly.GetName().Version?.ToString();
        this.ActivitySource = new ActivitySource(ActivitySourceName, ActivitySourceVersion);
        this.meter = new Meter(MeterName, version);
    }

    public ActivitySource ActivitySource { get; }

    public void Dispose()
    {
        this.ActivitySource.Dispose();
        this.meter.Dispose();
    }
}
