# Usa imagem Node.js slim
FROM node:20-slim

# Instala dependências do sistema para o Angular CLI (git, python, build-essential)
RUN apt-get update && apt-get install -y git python3 build-essential && apt-get clean

# Instala Angular CLI globalmente
RUN npm install -g @angular/cli@latest

# Define diretório de trabalho dentro do container
WORKDIR /app

# Copia package.json e package-lock.json (se tiver)
COPY package*.json ./

# Instala dependências (caso já tenha package.json)
RUN npm install || echo "Sem package.json, pulando npm install"

# Expõe a porta padrão do Angular
EXPOSE 4200

# Comando padrão para iniciar bash (para você entrar no container e criar projeto)
CMD [ "bash" ]
