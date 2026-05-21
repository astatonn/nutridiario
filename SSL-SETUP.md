# Guia de Configuração SSL - NutriDiario

Este guia explica como configurar SSL/HTTPS para o NutriDiario usando Let's Encrypt **SEM parar o Nginx**.

## Pré-requisitos

1. Domínio apontando para o servidor (DNS configurado)
2. Portas 80 e 443 abertas no firewall (já configurado via UFW)
3. Containers rodando (`docker ps` deve mostrar nginx, laravel e postgresql)

## Processo de Instalação SSL

### 1. Verificar DNS

Antes de instalar o certificado, verifique se o domínio está apontando corretamente:

```bash
# Verificar se o domínio aponta para este servidor
dig +short nutridiario.com.br
dig +short www.nutridiario.com.br

# Ou usar nslookup
nslookup nutridiario.com.br
nslookup www.nutridiario.com.br
```

O resultado deve mostrar o IP público deste servidor.

### 2. Verificar se Nginx está rodando

```bash
docker ps | grep nginx
# Deve mostrar o container nutridiario-nginx rodando
```

### 3. Instalar Certificado SSL

Execute o script de instalação:

```bash
./manage.sh ssl-install
```

Ou diretamente:

```bash
/srv/install-ssl.sh
```

**O que o script faz:**
- Obtém certificado SSL via certbot usando método webroot
- **NÃO para o nginx** - usa validação HTTP via /.well-known/acme-challenge/
- Copia certificados para /srv/nginx/ssl/
- Ativa configuração HTTPS no nginx
- Recarrega nginx sem downtime

### 4. Configurar Renovação Automática

Após a instalação bem-sucedida do certificado:

```bash
./manage.sh ssl-setup-auto-renew
```

Ou:

```bash
/srv/setup-auto-renew.sh
```

**O que isso configura:**
- Cronjob que executa diariamente às 3:30 AM
- Verifica se o certificado precisa renovação (30 dias antes do vencimento)
- Renova automaticamente **SEM parar o nginx**
- Recarrega nginx após renovação
- Logs em /srv/nginx/logs/ssl-renewal.log

## Comandos Disponíveis

### Via manage.sh

```bash
# Instalar certificado SSL (primeira vez)
./manage.sh ssl-install

# Configurar renovação automática
./manage.sh ssl-setup-auto-renew

# Renovar certificado manualmente
./manage.sh ssl-renew

# Ver status do certificado
./manage.sh ssl-status
```

### Diretamente

```bash
# Instalar certificado
/srv/install-ssl.sh

# Configurar renovação automática
/srv/setup-auto-renew.sh

# Renovar manualmente
/srv/renew-ssl.sh

# Ver informações do certificado
certbot certificates
```

## Arquivos Importantes

```
/srv/
├── install-ssl.sh          # Script de instalação inicial
├── renew-ssl.sh            # Script de renovação
├── post-renew.sh           # Executado após renovação bem-sucedida
├── setup-auto-renew.sh     # Configura cronjob
├── nginx/
│   ├── certbot/            # Webroot para validação Let's Encrypt
│   ├── ssl/                # Certificados SSL
│   │   ├── fullchain.pem
│   │   └── privkey.pem
│   └── logs/
│       └── ssl-renewal.log # Logs de renovação
└── /etc/cron.d/nutridiario-ssl-renew  # Cronjob de renovação
```

## Como Funciona a Renovação Automática

1. **Cronjob executa** `/srv/renew-ssl.sh` às 3:30 AM diariamente
2. **Certbot verifica** se o certificado expira em menos de 30 dias
3. **Se necessário**, renova usando método webroot (nginx continua rodando)
4. **Após renovação**, executa `/srv/post-renew.sh`:
   - Copia novos certificados para /srv/nginx/ssl/
   - Recarrega nginx (sem reiniciar, sem downtime)
5. **Registra tudo** em /srv/nginx/logs/ssl-renewal.log

## Verificações e Troubleshooting

### Verificar status do certificado

```bash
# Via manage.sh
./manage.sh ssl-status

# Diretamente
certbot certificates

# Ver quando expira
openssl x509 -in /srv/nginx/ssl/fullchain.pem -noout -dates
```

### Ver logs de renovação

```bash
tail -f /srv/nginx/logs/ssl-renewal.log
```

### Testar renovação manualmente

```bash
# Teste sem realmente renovar (dry-run)
certbot renew --dry-run --webroot --webroot-path /srv/nginx/certbot

# Renovar de verdade
./manage.sh ssl-renew
```

### Problemas comuns

#### Erro: "DNS problem: NXDOMAIN"

**Causa**: Domínio não está apontando para este servidor

**Solução**:
1. Verifique configuração DNS
2. Aguarde propagação DNS (até 48h)
3. Teste com: `dig +short nutridiario.com.br`

#### Erro: "Connection refused"

**Causa**: Nginx não está rodando ou porta 80 bloqueada

**Solução**:
```bash
# Verificar nginx
docker ps | grep nginx

# Verificar firewall
ufw status

# Testar acesso
curl -I http://nutridiario.com.br/.well-known/acme-challenge/test
```

#### Erro: "Certificate Authority failed to download"

**Causa**: Nginx não está servindo o diretório .well-known corretamente

**Solução**:
```bash
# Verificar configuração
docker exec nutridiario-nginx cat /etc/nginx/conf.d/nutridiario.conf | grep well-known

# Verificar volume montado
docker exec nutridiario-nginx ls -la /var/www/certbot

# Criar arquivo de teste
echo "test" > /srv/nginx/certbot/test.txt
curl http://nutridiario.com.br/.well-known/acme-challenge/test.txt
```

## Segurança

### Configurações SSL Implementadas

O certificado usa as seguintes configurações de segurança:

- **Protocolos**: TLS 1.2 e TLS 1.3 apenas
- **Ciphers**: Apenas ciphers fortes (ECDHE-RSA-AES256-GCM-SHA*)
- **HSTS**: Habilitado (max-age=31536000)
- **Headers de segurança**: X-Frame-Options, X-Content-Type-Options, etc.

### Arquivos de Certificado

```bash
# Permissões corretas
-rw-r--r-- fullchain.pem (644)
-rw------- privkey.pem (600)
```

### Renovação Automática

- Usa método webroot (mais seguro, sem parar serviços)
- Não expõe portas adicionais
- Logs auditáveis
- Recarrega nginx sem downtime

## Checklist de Configuração

- [ ] DNS apontando para o servidor
- [ ] Containers rodando (`docker ps`)
- [ ] Firewall configurado (UFW permite 80 e 443)
- [ ] Certificado SSL instalado (`./manage.sh ssl-install`)
- [ ] Renovação automática configurada (`./manage.sh ssl-setup-auto-renew`)
- [ ] Teste de renovação executado com sucesso (`certbot renew --dry-run`)
- [ ] Site acessível via HTTPS (`https://nutridiario.com.br`)
- [ ] Redirect HTTP→HTTPS funcionando

## Monitoramento

### Verificar próxima renovação

```bash
certbot certificates
# Mostra: "Expiry Date" e "Certificate Path"
```

### Verificar cronjob

```bash
cat /etc/cron.d/nutridiario-ssl-renew
```

### Ver histórico de renovações

```bash
cat /srv/nginx/logs/ssl-renewal.log
```

## Informações Adicionais

- **Validade do certificado**: 90 dias
- **Renovação automática**: 30 dias antes do vencimento
- **Rate limits Let's Encrypt**: 5 certificados/semana por domínio
- **Método de validação**: HTTP-01 (webroot)
- **Downtime durante renovação**: ZERO (nginx não para)

## Suporte

Se encontrar problemas:

1. Verifique logs: `/srv/nginx/logs/ssl-renewal.log`
2. Teste manualmente: `./manage.sh ssl-renew`
3. Verifique DNS: `dig +short nutridiario.com.br`
4. Verifique nginx: `docker logs nutridiario-nginx`
5. Execute security-check: `./manage.sh security-check`
