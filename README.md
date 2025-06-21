# GitHub User Info App

Aplicação fullstack para exibição de informações de usuários GitHub, desenvolvida com Laravel (backend) e Angular (frontend).

## 📖 Overview

A aplicação permite:

- Buscar por um nome de usuário GitHub
- Visualizar dados públicos do usuário
- Listagem com filtro de busca dos followings do usuário
- Armazenamento de logs para chamadas API
- Utilização de cache (Redis) nas rotas GET do backend

## 📋 Requisitos

- **PHP**: ^8.2
- **Laravel**: ^12.0
- **Composer**: ^2.0
- **Docker** e **Docker Compose**
- **PostgreSQL**: ^13
- **Redis**: ^6.0
- **Angular CLI**: ^17.0

## 🚀 Como Rodar o Projeto

1. **Clone o repositório**

   ```bash
   git clone https://github.com/wendreskennedy/github-user-info-app.git
   cd github-user-info-app
   ```

2. **Configure o ambiente do backend**

   ```bash
   cd backend
   cp .env.example .env
   cd ..
   ```

3. **Crie e inicie os containers**

   ```bash
   docker-compose build
   ```

   ```bash
   docker-compose up -d
   ```

4. **Configure a aplicação Laravel**

   ```bash
   docker exec -it github-user-info-app composer install
   docker exec -it github-user-info-app php artisan key:generate
   docker exec -it github-user-info-app php artisan migrate
   ```

5. **Acesse as aplicações**
   - **Backend API**: <http://localhost:8000>
   - **Frontend**: <http://localhost:4200>
   - **PostgreSQL**: localhost:5432
   - **Redis**: localhost:6379

## 🛠️ Comandos Úteis

### Backend (Laravel)

```bash
# Migrations
php artisan migrate                    # Executa migrations
php artisan migrate:rollback          # Desfaz última migration
php artisan migrate:fresh             # Recria todas as tabelas
php artisan make:migration nome        # Cria nova migration

# Cache
php artisan cache:clear               # Limpa cache
php artisan config:clear              # Limpa cache de configuração
php artisan route:clear               # Limpa cache de rotas

```

### Frontend (Angular)

```bash
# Desenvolvimento
ng serve                              # Inicia servidor de desenvolvimento
ng build                              # Build para produção
ng build --watch                      # Build com watch mode

# Geração de componentes
ng generate component nome            # Cria componente
ng generate service nome              # Cria service
ng generate module nome               # Cria módulo

```

### Docker

```bash
# Gerenciamento de containers
docker-compose up -d                  # Inicia containers em background
docker-compose down                   # Para e remove containers
docker-compose restart               # Reinicia containers
docker-compose logs -f                # Visualiza logs em tempo real

# Acesso aos containers
docker exec -it github-user-info-app bash          # Backend
docker exec -it github-user-info-app-frontend bash # Frontend
docker exec -it github-user-info-app-postgres psql -U admin -d github-user-info-app

# Rebuild containers
docker-compose build --no-cache       # Rebuild sem cache
docker-compose up --build             # Build e start
```

## 🧪 Testes Backend

```bash

docker exec -it github-user-info-app php artisan test

```

## 🔧 Troubleshooting

### Problemas Comuns

1. **Erro de permissão no Laravel**

   ```bash
   sudo chmod -R 775 backend/storage
   sudo chmod -R 775 backend/bootstrap/cache
   ```

2. **Erro de porta ocupada**

   ```bash
   # Verificar portas em uso
   lsof -i :8000  # Backend
   lsof -i :4200  # Frontend
   ```

3. **Problemas com Docker**

   ```bash
   docker system prune -a  # Limpa containers e imagens não utilizados
   ```

## 📚 Estrutura do Projeto

    github-user-info-app/
    ├── backend/           # API Laravel
    ├── frontend/          # Aplicação Angular
    ├── docker-compose.yml # Configuração Docker
    └── README.md         # Este arquivo
