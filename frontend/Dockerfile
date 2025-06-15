FROM node:20-slim

WORKDIR /app

# Copia apenas o package.json e package-lock.json para aproveitar cache
COPY package*.json ./

# Instala dependências (inclui Angular CLI)
RUN npm install

# Copia o restante dos arquivos
COPY . .

EXPOSE 4200

CMD ["npm", "start"]
