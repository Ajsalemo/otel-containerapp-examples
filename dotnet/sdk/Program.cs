using OpenTelemetry.Logs;
using OpenTelemetry.Metrics;
using OpenTelemetry.Resources;
using OpenTelemetry.Trace;
using OpenTelemetry.Exporter;
using OpenTelemetry.Instrumentation.AspNetCore;
using sdk.Otel;

var builder = WebApplication.CreateBuilder(args);
var instrumentation = new Instrumentation();

Uri otlpEndpointName = new(System.Environment.GetEnvironmentVariable("OTEL_EXPORTER_OTLP_ENDPOINT") ?? "http://localhost:4317");
string serviceName = System.Environment.GetEnvironmentVariable("OTEL_SERVICE_NAME") ?? "otel-examples-dotnet-sdk";

builder.Services.AddSingleton<Instrumentation>();

// Write OTEL telemetry to console in development environments
if (builder.Environment.IsDevelopment())
{
    builder.Logging.AddOpenTelemetry(options =>
    {
        options
            .SetResourceBuilder(
                ResourceBuilder.CreateDefault()
                    .AddService(serviceName))
                    .AddConsoleExporter();
    });
    builder.Services.AddOpenTelemetry()
          .ConfigureResource(resource => resource.AddService(serviceName))
          .WithTracing(tracing => tracing
              .AddAspNetCoreInstrumentation()
              .AddConsoleExporter())
          .WithMetrics(metrics => metrics
              .AddAspNetCoreInstrumentation()
              .AddConsoleExporter())
           .WithLogging(logging => logging
              .AddConsoleExporter());
}
// Otherwise send this telemetry to the OTEL collector - this defaults to using gRPC
else
{
    builder.Logging.AddOpenTelemetry(options =>
    {
        options
            .SetResourceBuilder(
                ResourceBuilder.CreateDefault()
                    .AddService(serviceName))
                    .AddOtlpExporter(options =>
                    {
                        options.Endpoint = otlpEndpointName;
                    });
    });
    builder.Services.AddOpenTelemetry()
          .ConfigureResource(resource => resource.AddService(serviceName))
          .WithTracing(tracing => tracing
              .AddSource(instrumentation.ActivitySourceName)
              .SetSampler(new AlwaysOnSampler())
              .AddAspNetCoreInstrumentation()
              .AddHttpClientInstrumentation()
              .AddOtlpExporter(options =>
                    {
                        options.Endpoint = otlpEndpointName;
                    }))
          .WithMetrics(metrics => metrics
              .AddMeter(instrumentation.MeterName)
              .SetExemplarFilter(ExemplarFilterType.TraceBased)
              .AddRuntimeInstrumentation()
              .AddHttpClientInstrumentation()
              .AddAspNetCoreInstrumentation()
              .AddOtlpExporter(options =>
                    {
                        options.Endpoint = otlpEndpointName;
                    }))
           .WithLogging(logging => logging
              .AddOtlpExporter(options =>
                    {
                        options.Endpoint = otlpEndpointName;
                    }));
}

// Add services to the container.
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.MapControllers();
app.UseHttpsRedirection();
app.Run();

