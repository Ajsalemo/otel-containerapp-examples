FROM golang:1.24.3-alpine3.20

WORKDIR /app

# Download Go modules
COPY go.mod go.sum ./
RUN go mod download

COPY . ./

# Build
RUN CGO_ENABLED=0 GOOS=linux go build -o /usr/local/bin/app

EXPOSE 3000

# Run
CMD ["/usr/local/bin/app"]