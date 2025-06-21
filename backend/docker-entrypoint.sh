#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Verifica se as dependências do Composer estão instaladas
echo "📦 Checking Composer dependencies..."
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "📥 Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "✅ Composer dependencies already installed."
fi

# Aguarda o banco de dados estar pronto e executa migrations
echo "🗄️  Checking database connection..."
timeout=30
while ! php artisan migrate:status > /dev/null 2>&1; do
    timeout=$((timeout - 1))
    if [ $timeout -eq 0 ]; then
        echo "⚠️  Database not ready, skipping migrations"
        break
    fi
    echo "⏳ Waiting for database... ($timeout seconds remaining)"
    sleep 1
done

if [ $timeout -gt 0 ]; then
    echo "🔄 Running database migrations..."
    php artisan migrate --force
fi

# Limpa cache (útil para desenvolvimento)
echo "🧹 Clearing application cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🌟 Starting Laravel development server on http://0.0.0.0:8000"
exec php artisan serve --host=0.0.0.0 --port=8000 