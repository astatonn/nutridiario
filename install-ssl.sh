#!/bin/bash

# Script para obter certificado SSL usando certbot com método webroot
# Este script obtém o certificado SEM parar o nginx

set -e

DOMAIN="nutridiario.com.br"
WEBROOT="/srv/nginx/certbot"
SSL_DIR="/srv/nginx/ssl"
NGINX_CONF="/srv/nginx/conf.d/nutridiario.conf"

echo "=== Instalação de Certificado SSL para NutriDiario ==="
echo ""
echo "IMPORTANTE: Certifique-se que o domínio $DOMAIN está apontando para este servidor!"
echo ""
read -p "Continuar com a instalação do certificado? (s/n): " confirm

if [ "$confirm" != "s" ]; then
    echo "Instalação cancelada."
    exit 0
fi

# Verificar se nginx está rodando
if ! docker ps | grep -q nutridiario-nginx; then
    echo "ERRO: Nginx não está rodando!"
    echo "Execute: cd /srv && docker compose up -d"
    exit 1
fi

# Obter certificado usando webroot (SEM parar nginx)
echo ""
echo "Obtendo certificado SSL..."
certbot certonly \
    --webroot \
    --webroot-path "$WEBROOT" \
    -d "$DOMAIN" \
    -d "www.$DOMAIN" \
    --agree-tos \
    --register-unsafely-without-email \
    --non-interactive

if [ $? -ne 0 ]; then
    echo ""
    echo "ERRO ao obter certificado!"
    echo "Verifique se:"
    echo "  1. O domínio está apontando para este servidor"
    echo "  2. As portas 80 e 443 estão abertas no firewall"
    echo "  3. O nginx está rodando corretamente"
    exit 1
fi

# Copiar certificados para o diretório do nginx
echo ""
echo "Copiando certificados..."
cp "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" "$SSL_DIR/"
cp "/etc/letsencrypt/live/$DOMAIN/privkey.pem" "$SSL_DIR/"

# Ajustar permissões
chmod 644 "$SSL_DIR/fullchain.pem"
chmod 600 "$SSL_DIR/privkey.pem"

# Descomentar bloco HTTPS no nginx.conf
echo ""
echo "Ativando configuração HTTPS..."

# Criar backup da configuração
cp "$NGINX_CONF" "${NGINX_CONF}.bak"

# Descomentar bloco HTTPS
sed -i 's/^#//' "$NGINX_CONF"

# Reativar redirect para HTTPS
sed -i 's/# Temporariamente sem redirect para obter certificado SSL/# Redirect HTTP to HTTPS/g' "$NGINX_CONF"
sed -i '/Aguardando configuração SSL/,+2d' "$NGINX_CONF"
sed -i 's/# Redirect HTTP to HTTPS/# Redirect HTTP to HTTPS\n    location \/ {\n        return 301 https:\/\/$host$request_uri;\n    }/g' "$NGINX_CONF"

# Recarregar nginx
echo ""
echo "Recarregando nginx..."
docker exec nutridiario-nginx nginx -t && docker exec nutridiario-nginx nginx -s reload

if [ $? -eq 0 ]; then
    echo ""
    echo "=== Certificado SSL instalado com sucesso! ==="
    echo ""
    echo "Seu site agora está disponível em:"
    echo "  https://$DOMAIN"
    echo "  https://www.$DOMAIN"
    echo ""
    echo "Configuração de renovação automática:"
    echo "  Execute: /srv/setup-auto-renew.sh"
    echo ""
else
    echo ""
    echo "ERRO ao recarregar nginx!"
    echo "Restaurando configuração anterior..."
    cp "${NGINX_CONF}.bak" "$NGINX_CONF"
    docker exec nutridiario-nginx nginx -s reload
    exit 1
fi
