# NutriDiario - Infraestrutura Docker

Infraestrutura completa e segura para a aplicação NutriDiario usando Docker.

## Estrutura de Diretórios

```
/srv/
├── docker-compose.yml          # Orquestração dos containers
├── .env                        # Variáveis de ambiente (SENSÍVEL)
├── nginx/                      # Configuração do Nginx
│   ├── conf.d/
│   │   └── nutridiario.conf   # Configuração do virtual host
│   ├── ssl/                   # Certificados SSL (adicionar manualmente)
│   └── logs/                  # Logs do Nginx
├── postgresql/                 # Banco de dados PostgreSQL
│   ├── data/                  # Dados persistentes do PostgreSQL
│   └── init/                  # Scripts de inicialização
│       └── 01-init.sql
└── laravel/                    # Aplicação Laravel
    ├── Dockerfile
    ├── .dockerignore
    ├── app/                   # Código-fonte da aplicação Laravel
    ├── storage/               # Storage do Laravel
    └── logs/                  # Logs da aplicação
```

## Arquitetura de Segurança

### Rede Isolada
- Todos os containers estão na rede `nutridiario-network` (172.20.0.0/16)
- PostgreSQL e Laravel **NÃO** expõem portas para o host
- Apenas Nginx expõe portas 80 e 443

### Firewall (UFW)
- Apenas portas 80, 443 e 22 (SSH) estão abertas
- Default: deny incoming, allow outgoing

### Containers
- Nginx: Reverse proxy com rate limiting e headers de segurança
- Laravel: Roda com usuário não-root, PHP-FPM otimizado
- PostgreSQL: Acesso restrito apenas aos containers da rede interna

## Configuração Inicial

### 1. Configurar DNS

Primeiro, configure seu domínio para apontar para o IP deste servidor:

```
nutridiario.com.br     -> A    -> [IP do servidor]
www.nutridiario.com.br -> A    -> [IP do servidor]
```

Aguarde a propagação DNS (pode levar até 48h, mas geralmente é rápido).

### 2. Instalar Certificado SSL

**IMPORTANTE**: Certbot já está instalado. Use o método webroot que NÃO para o nginx:

```bash
# Instalar certificado SSL (SEM parar nginx)
./manage.sh ssl-install

# Configurar renovação automática
./manage.sh ssl-setup-auto-renew
```

**Documentação completa**: Veja `/srv/SSL-SETUP.md` para detalhes e troubleshooting.

### 3. Adicionar Aplicação Laravel

Coloque o código da sua aplicação Laravel em `/srv/laravel/app/`:

```bash
# Exemplo: clonar repositório
cd /srv/laravel/app
git clone https://github.com/seu-usuario/nutridiario.git .

# Ou fazer upload dos arquivos via SCP/SFTP
```

### 3. Ajustar Permissões da Aplicação

```bash
# Dar permissões corretas para storage e cache
chmod -R 775 /srv/laravel/app/storage
chmod -R 775 /srv/laravel/app/bootstrap/cache
```

### 4. Iniciar os Containers

```bash
cd /srv
./manage.sh start
```

### 5. Instalar Dependências do Laravel

```bash
# Entrar no container do Laravel
docker exec -it nutridiario-app bash

# Instalar dependências do Composer
composer install --no-dev --optimize-autoloader

# Gerar chave da aplicação (se ainda não tiver)
php artisan key:generate

# Rodar migrations
php artisan migrate --force

# Cache de configuração para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Sair do container
exit
```

## Comandos Úteis

### Gerenciamento dos Containers

```bash
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Ver logs
docker-compose logs -f

# Ver logs de um serviço específico
docker-compose logs -f nginx
docker-compose logs -f laravel
docker-compose logs -f postgresql

# Reiniciar um serviço
docker-compose restart nginx

# Reconstruir containers (após mudanças no Dockerfile)
docker-compose up -d --build
```

### Acesso aos Containers

```bash
# Entrar no container do Laravel
docker exec -it nutridiario-app bash

# Entrar no container do PostgreSQL
docker exec -it nutridiario-db psql -U nutridiario_user -d nutridiario

# Executar comandos artisan
docker exec -it nutridiario-app php artisan [comando]
```

### Backup do Banco de Dados

```bash
# Backup
docker exec nutridiario-db pg_dump -U nutridiario_user nutridiario > backup_$(date +%Y%m%d_%H%M%S).sql

# Restaurar
docker exec -i nutridiario-db psql -U nutridiario_user nutridiario < backup.sql
```

## Monitoramento

### Ver Status dos Containers

```bash
docker-compose ps
```

### Verificar Uso de Recursos

```bash
docker stats
```

### Logs do Nginx

```bash
# Access log
tail -f /srv/nginx/logs/nutridiario-access.log

# Error log
tail -f /srv/nginx/logs/nutridiario-error.log
```

## Manutenção

### Atualizar Imagens

```bash
cd /srv
docker-compose pull
docker-compose up -d
```

### Limpar Containers e Imagens Antigas

```bash
# Remover containers parados
docker container prune -f

# Remover imagens não utilizadas
docker image prune -a -f

# Remover volumes não utilizados (CUIDADO!)
docker volume prune -f
```

### Renovar Certificado SSL

A renovação automática já está configurada! Após executar `./manage.sh ssl-setup-auto-renew`, o sistema:

- Verifica diariamente às 3:30 AM se o certificado precisa renovação
- Renova automaticamente 30 dias antes do vencimento
- **NÃO para o nginx** durante a renovação (zero downtime)
- Registra tudo em `/srv/nginx/logs/ssl-renewal.log`

Para renovar manualmente:

```bash
./manage.sh ssl-renew
```

Para verificar status do certificado:

```bash
./manage.sh ssl-status
```

## Segurança

### Checklist de Segurança

- [ ] Certificado SSL configurado e válido
- [ ] Arquivo `.env` com permissões restritas (600)
- [ ] Senhas fortes no arquivo `.env`
- [ ] UFW ativo e configurado
- [ ] Fail2ban ativo e monitorando
- [ ] Backups automáticos configurados
- [ ] Logs sendo monitorados
- [ ] Atualizações do sistema aplicadas regularmente

### Proteger Arquivo .env

```bash
chmod 600 /srv/.env
chown root:root /srv/.env
```

### Verificar Portas Expostas

```bash
# Ver portas abertas no firewall
ufw status

# Ver portas escutando
netstat -tlnp

# Ver containers e portas
docker ps
```

## Troubleshooting

### Container não inicia

```bash
# Ver logs do container
docker-compose logs [nome-do-serviço]

# Verificar configuração
docker-compose config
```

### Erro de conexão com banco de dados

```bash
# Verificar se PostgreSQL está rodando
docker-compose ps postgresql

# Ver logs do PostgreSQL
docker-compose logs postgresql

# Testar conexão
docker exec nutridiario-db pg_isready -U nutridiario_user
```

### Nginx retorna 502 Bad Gateway

```bash
# Verificar se Laravel está rodando
docker-compose ps laravel

# Ver logs do Laravel
docker-compose logs laravel

# Verificar comunicação entre containers
docker exec nutridiario-nginx ping laravel
```

## Contatos e Suporte

Para problemas ou dúvidas, verifique os logs e documentação do Laravel, Nginx e PostgreSQL.
