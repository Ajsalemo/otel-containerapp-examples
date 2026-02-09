FROM mcr.microsoft.com/dotnet/aspnet:10.0 AS base
WORKDIR /app
EXPOSE 8080

FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src
COPY ["sdk.csproj", "."]
RUN dotnet restore "sdk.csproj"
COPY . .
WORKDIR "/src/"
RUN dotnet build "sdk.csproj" -c Release -o /app/build

FROM build AS publish
RUN dotnet publish "sdk.csproj" -c Release -o /app/publish

FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "sdk.dll"]


