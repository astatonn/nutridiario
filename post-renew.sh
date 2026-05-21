#!/bin/bash

# Script executado após renovação bem-sucedida do certificado
# Este script é chamado pelo certbot via --deploy-hook

DOMAIN="nutridiario.com.br"
SSL_DIR="/srv/nginx/ssl"
LOG_FILE="/srv/nginx/logs/ssl-renewal.log"

# Função para log
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_FILE"
}

log "Certificado renovado! Atualizando arquivos..."

# Copiar novos certificados
cp "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" "$SSL_DIR/"
cp "/etc/letsencrypt/live/$DOMAIN/privkey.pem" "$SSL_DIR/"

# Ajustar permissões
chmod 644 "$SSL_DIR/fullchain.pem"
chmod 600 "$SSL_DIR/privkey.pem"

log "Certificados copiados. Recarregando nginx..."

# Recarregar nginx (sem reiniciar)
docker exec nutridiario-nginx nginx -s reload

if [ $? -eq 0 ]; then
    log "Nginx recarregado com sucesso!"
else
    log "ERRO ao recarregar nginx!"
    exit 1
fi

log "Renovação completa!"
