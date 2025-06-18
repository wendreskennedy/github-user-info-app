FROM node:20-alpine

RUN npm install -g @angular/cli@17

WORKDIR /app

COPY . .

RUN npm install

EXPOSE 4200

CMD ["npm", "start"]
