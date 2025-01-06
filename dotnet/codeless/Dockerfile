FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS base
WORKDIR /app
EXPOSE 8080

FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src
COPY ["codeless.csproj", "."]
RUN dotnet restore --runtime linux-x64 "codeless.csproj"
COPY . .
WORKDIR "/src/"
RUN dotnet build --runtime linux-x64 "codeless.csproj" -c Release -o /app/build

FROM build AS publish
RUN dotnet publish --runtime linux-x64 "codeless.csproj" -c Release -o /app/publish

FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
# See: https://opentelemetry.io/docs/zero-code/net/nuget-packages/
ENTRYPOINT ["/app/instrument.sh", "dotnet", "codeless.dll"]


