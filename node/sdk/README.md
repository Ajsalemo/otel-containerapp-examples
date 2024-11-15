# otel-sdk-examples-node-sdk

**Local development**:
1. Build and run the image
2. When running the container, pass the following environments variables to it so otel telemetry logs, traces, metrics, are logged to the console:
- `NODE_ENV=dev`


**Production / deploying to Container Apps**:
1. Build the image - deploy this to a container registry like Azure Container Registry. **NOTE**: Don't  set `NODE_ENV` to `dev`.


Limitations for the codeless agent are called out [here](https://opentelemetry.io/docs/zero-code/js/#configuring-the-module)
