#!/bin/bash

# Script de configuração do certificado SSL para NutriDiario
# Este script deve ser executado apenas uma vez

set -e

DOMAIN="nutridiario.com.br"
EMAIL=""

echo "=== Configuração de SSL para NutriDiario ==="
echo ""

# Verificar se certbot está instalado
if ! command -v certbot &> /dev/null; then
    echo "Certbot não encontrado. Instalando..."
    apt-get update
    apt-get install -y certbot
fi

# Solicitar email
read -p "Digite seu email para notificações do Let's Encrypt: " EMAIL

if [ -z "$EMAIL" ]; then
    echo "Erro: Email é obrigatório!"
    exit 1
fi

# Parar nginx temporariamente
echo "Parando containers..."
cd /srv
docker-compose down

# Obter certificado
echo "Obtendo certificado SSL para $DOMAIN..."
certbot certonly --standalone \
    -d $DOMAIN \
    -d www.$DOMAIN \
    --email $EMAIL \
    --agree-tos \
    --non-interactive

# Copiar certificados
echo "Copiando certificados para /srv/nginx/ssl/..."
cp /etc/letsencrypt/live/$DOMAIN/fullchain.pem /srv/nginx/ssl/
cp /etc/letsencrypt/live/$DOMAIN/privkey.pem /srv/nginx/ssl/

# Ajustar permissões
chmod 644 /srv/nginx/ssl/fullchain.pem
chmod 600 /srv/nginx/ssl/privkey.pem

# Descomentar linhas SSL no nginx.conf
echo "Atualizando configuração do Nginx..."
sed -i 's/# ssl_certificate /ssl_certificate /g' /srv/nginx/conf.d/nutridiario.conf

# Iniciar containers
echo "Iniciando containers..."
docker-compose up -d

echo ""
echo "=== Configuração concluída com sucesso! ==="
echo ""
echo "Certificado SSL instalado e configurado."
echo "Seu site agora está disponível em https://$DOMAIN"
echo ""
echo "Para renovar o certificado automaticamente, execute:"
echo "  crontab -e"
echo ""
echo "E adicione a seguinte linha:"
echo "  0 3 1 * * /srv/manage.sh ssl-renew"
echo ""
