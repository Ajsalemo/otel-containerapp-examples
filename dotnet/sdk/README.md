# otel-sdk-examples-dotnet-sdk
This application is configured to use the [OpenTelemetry Dotnet SDK](https://opentelemetry.io/docs/languages/net/)

**Local development**:
1. Build and run the image


**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry
2. Create a Container App with [OpenTelemetry enabled](https://learn.microsoft.com/en-us/azure/container-apps/opentelemetry-agents?tabs=azure-cli#environment-variables) enabled on the environment. 