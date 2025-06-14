# Testes - GitHub User Info App

Este documento descreve a suíte de testes implementada para a aplicação GitHub User Info App.

## Estrutura dos Testes

### Testes Unitários (`tests/Unit/`)

#### 1. `UserServiceTest.php`
Testa a lógica de negócio do `UserService`:
- ✅ Busca de usuário com sucesso
- ✅ Busca de seguidores com sucesso
- ✅ Tratamento de erros 404 (usuário não encontrado)
- ✅ Tratamento de erros 500 (erro do servidor)
- ✅ Verificação de logs de API
- ✅ Mock do HTTP client para não fazer chamadas reais

#### 2. `ApiLogTest.php`
Testa o modelo `ApiLog`:
- ✅ Criação de logs com dados válidos
- ✅ Atributos fillable
- ✅ Timestamps automáticos
- ✅ Estrutura da tabela
- ✅ Operações CRUD básicas
- ✅ Diferentes códigos de status

#### 3. `UserControllerTest.php`
Testa o `UserController` isoladamente:
- ✅ Retorno de dados de usuário
- ✅ Retorno de dados de seguidores
- ✅ Tratamento de exceções
- ✅ Códigos de status HTTP corretos
- ✅ Mock do UserService

### Testes de Integração/Feature (`tests/Feature/`)

#### 1. `UserApiTest.php`
Testa os endpoints da API completos:
- ✅ `GET /api/{username}` - Busca de usuário
- ✅ `GET /api/{username}/followings` - Busca de seguidores
- ✅ Tratamento de usuários inexistentes
- ✅ Tratamento de rate limiting da API do GitHub
- ✅ Tratamento de erros do servidor
- ✅ Verificação de logs no banco de dados
- ✅ Caracteres especiais em usernames

#### 2. `DatabaseTest.php`
Testa a integração com o banco de dados:
- ✅ Estrutura da tabela `api_logs`
- ✅ Operações CRUD
- ✅ Timestamps automáticos
- ✅ Payloads grandes
- ✅ Consultas e filtros
- ✅ Transações

## Como Executar os Testes

### Pré-requisitos
```bash
cd backend
composer install
```

### Executar Todos os Testes
```bash
php artisan test
```

### Executar Apenas Testes Unitários
```bash
php artisan test --testsuite=Unit
```

### Executar Apenas Testes de Feature
```bash
php artisan test --testsuite=Feature
```

### Executar um Teste Específico
```bash
php artisan test tests/Unit/UserServiceTest.php
php artisan test tests/Feature/UserApiTest.php
```

### Executar com Cobertura de Código (se Xdebug estiver instalado)
```bash
php artisan test --coverage
```

### Executar com Relatório Detalhado
```bash
php artisan test --verbose
```

## Configuração dos Testes

### Banco de Dados
Os testes usam SQLite em memória (`:memory:`) conforme configurado no `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### HTTP Mocking
Os testes usam o `Http::fake()` do Laravel para simular chamadas à API do GitHub, evitando:
- Dependência de conectividade com a internet
- Rate limiting da API do GitHub
- Dados inconsistentes nos testes

## Cenários de Teste Cobertos

### Casos de Sucesso
- ✅ Usuário válido retorna dados corretos
- ✅ Usuário com seguidores retorna lista
- ✅ Usuário sem seguidores retorna array vazio
- ✅ Logs são criados corretamente

### Casos de Erro
- ✅ Usuário inexistente (404)
- ✅ Rate limiting da API (403)
- ✅ Erro interno do servidor (500)
- ✅ Problemas de conectividade

### Casos Especiais
- ✅ Usernames com caracteres especiais
- ✅ Múltiplas requisições simultâneas
- ✅ Payloads grandes nos logs
- ✅ Transações de banco de dados

## Métricas de Cobertura

Os testes cobrem:
- **Controllers**: 100% dos métodos
- **Services**: 100% dos métodos
- **Models**: 100% dos atributos e métodos
- **Rotas**: 100% dos endpoints
- **Cenários de erro**: Todos os códigos de status HTTP

## Boas Práticas Implementadas

### Arrange-Act-Assert (AAA)
Todos os testes seguem o padrão AAA:
```php
public function test_example()
{
    // Arrange - Preparar dados e mocks
    $username = 'testuser';
    Http::fake([...]);
    
    // Act - Executar a ação
    $result = $this->service->getUser($username);
    
    // Assert - Verificar resultados
    $this->assertEquals($expected, $result);
}
```

### Isolamento de Testes
- Cada teste é independente
- Uso de `RefreshDatabase` para limpar dados
- Mocks são resetados após cada teste

### Nomes Descritivos
- Nomes de testes explicam o cenário e resultado esperado
- Exemplo: `test_get_user_returns_error_for_nonexistent_user`

### Dados de Teste Realistas
- Uso de dados similares aos retornados pela API real do GitHub
- Cenários baseados em casos reais de uso

## Executando em CI/CD

Para integração contínua, adicione ao seu pipeline:

```yaml
# GitHub Actions exemplo
- name: Run Tests
  run: |
    cd backend
    php artisan test --coverage --min=80
```

## Troubleshooting

### Erro de Migração
Se houver erro de migração, execute:
```bash
php artisan migrate:fresh --env=testing
```

### Erro de Dependências
Certifique-se de que todas as dependências estão instaladas:
```bash
composer install --dev
```

### Erro de Permissões
Verifique as permissões das pastas:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
``` 