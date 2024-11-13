az containerapp env telemetry app-insights set --resource-group "some-rg" \
    --name "someenv" \
    --connection-string "some-connection-string" \
     --enable-open-telemetry-traces true \
     --enable-open-telemetry-logs true

