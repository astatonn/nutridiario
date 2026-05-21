#!/bin/bash

# Script de renovação automática de certificado SSL
# Este script renova o certificado SEM parar o nginx

set -e

DOMAIN="nutridiario.com.br"
WEBROOT="/srv/nginx/certbot"
SSL_DIR="/srv/nginx/ssl"
LOG_FILE="/srv/nginx/logs/ssl-renewal.log"

# Função para log
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

log "=== Iniciando renovação de certificado SSL ==="

# Verificar se nginx está rodando
if ! docker ps | grep -q nutridiario-nginx; then
    log "ERRO: Nginx não está rodando!"
    exit 1
fi

# Tentar renovar certificado
log "Tentando renovar certificado..."
certbot renew \
    --webroot \
    --webroot-path "$WEBROOT" \
    --deploy-hook "/srv/post-renew.sh" \
    --quiet

if [ $? -eq 0 ]; then
    log "Renovação concluída com sucesso!"
else
    log "Nenhum certificado precisou ser renovado ou houve erro."
fi

log "=== Renovação finalizada ==="
