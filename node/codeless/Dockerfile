FROM node:22.2.0-alpine3.19 as build

RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

COPY package.json package-lock.json /usr/src/app/
RUN npm i && npm i -g pm2
COPY . /usr/src/app

FROM build as production
WORKDIR /usr/src/app
COPY --from=build /usr/src/app .

EXPOSE 3000

ENTRYPOINT [ "/usr/src/app/init_container.sh" ] 