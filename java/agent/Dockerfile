FROM maven:3.9.12-eclipse-temurin as build
WORKDIR /usr/src/app
COPY . /usr/src/app

RUN mvn -f /usr/src/app/pom.xml clean package -DskipTests

FROM eclipse-temurin:21.0.5_11-jdk-jammy
WORKDIR /usr/src/app

COPY --from=build /usr/src/app/target/sdk-0.0.1-SNAPSHOT.jar sdk-0.0.1-SNAPSHOT.jar
COPY --from=build /usr/src/app/init_container.sh init_container.sh 
COPY --from=build /usr/src/app/otel/opentelemetry-javaagent.jar /usr/src/app/otel/opentelemetry-javaagent.jar

EXPOSE 8080

ENTRYPOINT [ "/usr/src/app/init_container.sh" ]