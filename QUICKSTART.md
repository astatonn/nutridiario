# Guia de Início Rápido - NutriDiario

## Pré-requisitos

- VPS com Docker instalado
- Domínio apontando para o IP da VPS (nutridiario.com.br)
- Acesso root via SSH

## Passos para Iniciar

### 1. Adicionar sua aplicação Laravel

```bash
cd /srv/laravel/app
# Clonar seu repositório OU fazer upload dos arquivos
git clone https://github.com/seu-usuario/nutridiario.git .
```

### 2. Criar diretórios necessários do Laravel

```bash
cd /srv/laravel/app
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
```

### 3. Configurar permissões

```bash
chmod -R 775 /srv/laravel/app/storage
chmod -R 775 /srv/laravel/app/bootstrap/cache
```

### 4. Configurar DNS

Configure seu domínio para apontar para o IP deste servidor:
- nutridiario.com.br → IP do servidor
- www.nutridiario.com.br → IP do servidor

Aguarde a propagação DNS (teste com: `dig +short nutridiario.com.br`)

### 5. Instalar certificado SSL (IMPORTANTE)

```bash
cd /srv
# Instalar certificado (SEM parar nginx)
./manage.sh ssl-install

# Configurar renovação automática
./manage.sh ssl-setup-auto-renew
```

**Veja `/srv/SSL-SETUP.md` para documentação completa.**

### 6. Iniciar os containers

```bash
cd /srv
./manage.sh start
```

### 7. Instalar dependências do Laravel

```bash
./manage.sh shell-app

# Dentro do container:
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exit
```

### 8. Verificar se está funcionando

```bash
# Ver status dos containers
./manage.sh status

# Ver logs
./manage.sh logs
```

Acesse: https://nutridiario.com.br

## Comandos Úteis

```bash
# Ver todos os comandos disponíveis
./manage.sh

# Ver logs de um serviço específico
./manage.sh logs nginx
./manage.sh logs laravel

# Executar comandos artisan
./manage.sh artisan migrate
./manage.sh artisan tinker

# Fazer backup do banco
./manage.sh backup-db

# Verificar segurança
./manage.sh security-check
```

## Troubleshooting

### Erro 502 Bad Gateway

```bash
# Verificar se Laravel está rodando
./manage.sh status

# Ver logs do Laravel
./manage.sh logs laravel

# Reiniciar containers
./manage.sh restart
```

### Erro de conexão com banco de dados

```bash
# Entrar no shell do banco
./manage.sh shell-db

# Verificar se as credenciais em .env estão corretas
cat /srv/.env
```

### Certificado SSL não funciona

```bash
# Verificar se os certificados existem
ls -la /srv/nginx/ssl/

# Verificar logs do Nginx
./manage.sh logs nginx

# Reconfigurar SSL
./setup-ssl.sh
```

## Segurança

Sua infraestrutura já está configurada com:

- ✅ Firewall UFW (apenas portas 80, 443 e 22 abertas)
- ✅ Fail2ban instalado
- ✅ PostgreSQL não exposto externamente
- ✅ Laravel não exposto externamente
- ✅ Rede Docker isolada
- ✅ Credenciais fortes geradas automaticamente
- ✅ Headers de segurança no Nginx
- ✅ Rate limiting configurado
- ✅ SSL/TLS com configurações seguras

### Checklist pós-instalação

Execute o comando de verificação:

```bash
./manage.sh security-check
```

## Próximos Passos

1. Configure renovação automática do SSL (cronjob)
2. Configure backups automáticos do banco de dados
3. Configure monitoramento (opcional)
4. Revise e ajuste as configurações do Laravel em `/srv/.env`
