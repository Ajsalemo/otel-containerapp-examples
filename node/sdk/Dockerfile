FROM node:22.2.0-alpine3.19 as build

RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

COPY package.json package-lock.json /usr/src/app/
RUN npm i && npm i pm2 -g

COPY . /usr/src/app

FROM build as production
WORKDIR /usr/src/app

COPY --from=build /usr/src/app .

RUN chmod +x /usr/src/app/init_container.sh

EXPOSE 3000

ENTRYPOINT [ "/usr/src/app/init_container.sh" ] 