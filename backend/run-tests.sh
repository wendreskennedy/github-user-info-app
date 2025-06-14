#!/bin/bash

# Script para executar testes da aplicação GitHub User Info App
# Uso: ./run-tests.sh [opção]

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para imprimir cabeçalho
print_header() {
    echo -e "${BLUE}================================${NC}"
    echo -e "${BLUE}  GitHub User Info App - Tests  ${NC}"
    echo -e "${BLUE}================================${NC}"
    echo ""
}

# Função para imprimir sucesso
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

# Função para imprimir erro
print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Função para imprimir aviso
print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Função para imprimir informação
print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Verificar se estamos no diretório correto
check_directory() {
    if [ ! -f "artisan" ]; then
        print_error "Este script deve ser executado no diretório backend/"
        exit 1
    fi
}

# Verificar dependências
check_dependencies() {
    print_info "Verificando dependências..."
    
    if [ ! -d "vendor" ]; then
        print_warning "Pasta vendor não encontrada. Executando composer install..."
        composer install
    fi
    
    print_success "Dependências verificadas"
}

# Executar todos os testes
run_all_tests() {
    print_info "Executando todos os testes..."
    php artisan test --verbose
    print_success "Todos os testes executados"
}

# Executar apenas testes unitários
run_unit_tests() {
    print_info "Executando testes unitários..."
    php artisan test --testsuite=Unit --verbose
    print_success "Testes unitários executados"
}

# Executar apenas testes de feature
run_feature_tests() {
    print_info "Executando testes de feature/integração..."
    php artisan test --testsuite=Feature --verbose
    print_success "Testes de feature executados"
}

# Executar testes com cobertura
run_coverage_tests() {
    print_info "Executando testes com cobertura de código..."
    
    # Verificar se Xdebug está instalado
    if ! php -m | grep -q xdebug; then
        print_warning "Xdebug não está instalado. Cobertura de código não disponível."
        print_info "Para instalar Xdebug: sudo apt-get install php-xdebug (Ubuntu/Debian)"
        return 1
    fi
    
    php artisan test --coverage --min=80
    print_success "Testes com cobertura executados"
}

# Executar teste específico
run_specific_test() {
    if [ -z "$2" ]; then
        print_error "Por favor, especifique o arquivo de teste"
        print_info "Exemplo: ./run-tests.sh specific tests/Unit/UserServiceTest.php"
        exit 1
    fi
    
    print_info "Executando teste específico: $2"
    php artisan test "$2" --verbose
    print_success "Teste específico executado"
}

# Executar testes em modo watch (requer inotify-tools)
run_watch_tests() {
    print_info "Executando testes em modo watch..."
    print_warning "Pressione Ctrl+C para parar"
    
    if ! command -v inotifywait &> /dev/null; then
        print_error "inotify-tools não está instalado"
        print_info "Para instalar: sudo apt-get install inotify-tools (Ubuntu/Debian)"
        exit 1
    fi
    
    while true; do
        php artisan test --quiet
        print_info "Aguardando mudanças nos arquivos..."
        inotifywait -r -e modify app/ tests/ --quiet
        clear
        print_info "Arquivos modificados. Executando testes novamente..."
    done
}

# Limpar cache e executar testes
run_fresh_tests() {
    print_info "Limpando cache e executando testes..."
    php artisan config:clear
    php artisan cache:clear
    php artisan test --verbose
    print_success "Testes executados com cache limpo"
}

# Executar testes em paralelo (Laravel 8+)
run_parallel_tests() {
    print_info "Executando testes em paralelo..."
    php artisan test --parallel --verbose
    print_success "Testes paralelos executados"
}

# Mostrar ajuda
show_help() {
    echo "Uso: ./run-tests.sh [opção]"
    echo ""
    echo "Opções disponíveis:"
    echo "  all         - Executar todos os testes (padrão)"
    echo "  unit        - Executar apenas testes unitários"
    echo "  feature     - Executar apenas testes de feature/integração"
    echo "  coverage    - Executar testes com cobertura de código"
    echo "  specific    - Executar um teste específico"
    echo "  watch       - Executar testes em modo watch (requer inotify-tools)"
    echo "  fresh       - Limpar cache e executar testes"
    echo "  parallel    - Executar testes em paralelo"
    echo "  help        - Mostrar esta ajuda"
    echo ""
    echo "Exemplos:"
    echo "  ./run-tests.sh"
    echo "  ./run-tests.sh unit"
    echo "  ./run-tests.sh specific tests/Unit/UserServiceTest.php"
    echo "  ./run-tests.sh coverage"
}

# Função principal
main() {
    print_header
    check_directory
    check_dependencies
    
    case "${1:-all}" in
        "all")
            run_all_tests
            ;;
        "unit")
            run_unit_tests
            ;;
        "feature")
            run_feature_tests
            ;;
        "coverage")
            run_coverage_tests
            ;;
        "specific")
            run_specific_test "$@"
            ;;
        "watch")
            run_watch_tests
            ;;
        "fresh")
            run_fresh_tests
            ;;
        "parallel")
            run_parallel_tests
            ;;
        "help"|"-h"|"--help")
            show_help
            ;;
        *)
            print_error "Opção inválida: $1"
            show_help
            exit 1
            ;;
    esac
    
    echo ""
    print_success "Script executado com sucesso!"
}

# Executar função principal
main "$@" 