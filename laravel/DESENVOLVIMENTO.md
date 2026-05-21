# Guia de Desenvolvimento - NutriDiario Laravel

## Aplicação Instalada e Funcionando

A aplicação Laravel está rodando em: **https://nutridiario.com.br**

## Estrutura da Aplicação

```
/srv/laravel/
├── app/                    # Código fonte da aplicação Laravel
│   ├── app/               # Models, Controllers, etc
│   ├── config/            # Arquivos de configuração
│   ├── database/          # Migrations e Seeders
│   ├── public/            # Arquivos públicos (index.php)
│   ├── resources/         # Views, CSS, JS
│   ├── routes/            # Definição de rotas
│   ├── storage/           # Logs, cache, uploads
│   └── .env              # Configurações do ambiente
├── storage/               # Storage persistente
└── logs/                  # Logs da aplicação
```

## Comandos Úteis

### Acessar o container Laravel

```bash
./manage.sh shell-app
```

### Executar comandos Artisan

```bash
# Via manage.sh (recomendado)
./manage.sh artisan migrate
./manage.sh artisan make:controller UserController
./manage.sh artisan make:model Product -m

# Diretamente
docker exec -u laravel nutridiario-app php artisan [comando]
```

### Comandos Artisan Comuns

```bash
# Migrations
./manage.sh artisan migrate                    # Rodar migrations
./manage.sh artisan migrate:rollback          # Reverter última migration
./manage.sh artisan migrate:fresh             # Dropar e recriar banco
./manage.sh artisan migrate:fresh --seed      # Dropar, recriar e popular

# Criar recursos
./manage.sh artisan make:model Product -mcr   # Model + Migration + Controller + Resource
./manage.sh artisan make:controller ApiController --api
./manage.sh artisan make:migration create_products_table
./manage.sh artisan make:seeder ProductSeeder
./manage.sh artisan make:factory ProductFactory
./manage.sh artisan make:request StoreProductRequest

# Cache (importante em produção)
./manage.sh artisan config:cache              # Cache de configuração
./manage.sh artisan route:cache               # Cache de rotas
./manage.sh artisan view:cache                # Cache de views
./manage.sh artisan cache:clear               # Limpar cache da aplicação

# Limpar todos os caches
./manage.sh artisan optimize:clear

# Otimizar para produção
./manage.sh artisan optimize

# Queue (filas)
./manage.sh artisan queue:work                # Processar filas
./manage.sh artisan queue:listen              # Escutar filas

# Tinker (console interativo)
./manage.sh artisan tinker
```

## Banco de Dados

### Informações de Conexão

- **Host**: postgresql (nome do container)
- **Porta**: 5432
- **Database**: nutridiario
- **Username**: nutridiario_user
- **Password**: Ver `/srv/.env`

### Acessar PostgreSQL

```bash
# Via manage.sh
./manage.sh shell-db

# Ver tabelas
\dt

# Ver estrutura de uma tabela
\d users

# Executar query
SELECT * FROM users;

# Sair
\q
```

### Backup e Restore

```bash
# Criar backup
./manage.sh backup-db

# Restaurar backup
./manage.sh restore-db backup_20250114_120000.sql
```

## Desenvolvimento Local

### Fazer alterações no código

```bash
# 1. Editar arquivos em /srv/laravel/app/
cd /srv/laravel/app

# 2. Se alterou rotas ou configurações, limpar cache
./manage.sh artisan optimize:clear

# 3. Se alterou banco de dados, rodar migrations
./manage.sh artisan migrate

# 4. Para produção, recriar caches
./manage.sh artisan config:cache
./manage.sh artisan route:cache
./manage.sh artisan view:cache
```

### Instalar pacotes com Composer

```bash
# Entrar no container
./manage.sh shell-app

# Instalar pacote
composer require nome/pacote

# Atualizar dependências
composer update

# Sair
exit
```

### Exemplo: Criar uma API REST básica

```bash
# 1. Criar Model, Migration e Controller
./manage.sh artisan make:model Product -mcr

# 2. Editar migration em /srv/laravel/app/database/migrations/
# Adicionar campos: name, description, price, stock

# 3. Rodar migration
./manage.sh artisan migrate

# 4. Editar Controller em /srv/laravel/app/app/Http/Controllers/ProductController.php
# Implementar métodos: index, store, show, update, destroy

# 5. Adicionar rotas em /srv/laravel/app/routes/api.php
# Route::apiResource('products', ProductController::class);

# 6. Testar API
curl https://nutridiario.com.br/api/products
```

## Logs

### Ver logs da aplicação

```bash
# Logs do Laravel
tail -f /srv/laravel/logs/*.log

# Logs do container Laravel
./manage.sh logs laravel

# Logs do Nginx
./manage.sh logs nginx

# Ver todos os logs
./manage.sh logs
```

## Debugging

### Ativar modo debug (CUIDADO: Apenas em desenvolvimento!)

```bash
# Editar /srv/laravel/app/.env
APP_DEBUG=true
APP_ENV=local

# Limpar cache
./manage.sh artisan config:clear

# IMPORTANTE: Reverter para produção depois!
APP_DEBUG=false
APP_ENV=production
```

### Ver informações da aplicação

```bash
# Informações do ambiente
./manage.sh artisan about

# Listar todas as rotas
./manage.sh artisan route:list

# Ver configurações
./manage.sh artisan config:show

# Testar conexão com banco
./manage.sh artisan tinker --execute="DB::connection()->getPdo()"
```

## Deploy de Alterações

Quando você fizer alterações no código:

```bash
# 1. Se alterou dependências
./manage.sh shell-app
composer install --no-dev --optimize-autoloader
exit

# 2. Se alterou banco de dados
./manage.sh artisan migrate --force

# 3. Limpar e recriar caches
./manage.sh artisan optimize:clear
./manage.sh artisan config:cache
./manage.sh artisan route:cache
./manage.sh artisan view:cache
./manage.sh artisan optimize

# 4. Reiniciar serviços se necessário
./manage.sh restart
```

## Estrutura de Pastas Recomendada

```
app/
├── Http/
│   ├── Controllers/       # Controllers da aplicação
│   ├── Middleware/        # Middlewares personalizados
│   └── Requests/          # Form Requests para validação
├── Models/               # Models Eloquent
├── Services/            # Lógica de negócio
└── Repositories/        # Repositórios (opcional)

database/
├── migrations/          # Migrations do banco
├── seeders/            # Seeders
└── factories/          # Factories para testes

resources/
├── views/              # Views Blade
├── js/                 # JavaScript
└── css/                # CSS

routes/
├── web.php             # Rotas web
├── api.php             # Rotas da API
└── console.php         # Comandos console
```

## Boas Práticas

1. **Sempre use migrations** para alterações no banco de dados
2. **Valide dados** com Form Requests
3. **Use Models** para interagir com o banco
4. **Separe lógica de negócio** em Services ou Actions
5. **Cache em produção** sempre que possível
6. **Não commite o .env** - use .env.example como template
7. **Use Queue** para tarefas pesadas (emails, processamento)
8. **Logs estruturados** para facilitar debug
9. **Testes automatizados** com PHPUnit

## Troubleshooting

### Erro 500

```bash
# Ver logs
./manage.sh logs laravel
tail -f /srv/laravel/logs/laravel.log

# Limpar cache
./manage.sh artisan optimize:clear
```

### Permissões

```bash
# Ajustar permissões
docker exec -u root nutridiario-app chown -R laravel:laravel /var/www/html
docker exec -u root nutridiario-app chmod -R 775 /var/www/html/storage
docker exec -u root nutridiario-app chmod -R 775 /var/www/html/bootstrap/cache
```

### Recriar aplicação do zero

```bash
# Backup do banco
./manage.sh backup-db

# Limpar aplicação
rm -rf /srv/laravel/app/*

# Recriar e seguir passos de instalação novamente
```

## Recursos

- **Laravel Docs**: https://laravel.com/docs
- **Laravel API**: https://laravel.com/api/master
- **Laracasts**: https://laracasts.com
- **Laravel News**: https://laravel-news.com

## Suporte

Para problemas específicos do Laravel:
1. Verifique os logs em `/srv/laravel/logs/`
2. Execute `./manage.sh artisan about` para ver configurações
3. Use `./manage.sh artisan tinker` para testar código
4. Consulte a documentação oficial do Laravel
