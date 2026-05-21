#!/bin/bash

# Script para configurar renovação automática de certificado SSL

set -e

CRON_FILE="/etc/cron.d/nutridiario-ssl-renew"
RENEW_SCRIPT="/srv/renew-ssl.sh"

echo "=== Configuração de Renovação Automática de SSL ==="
echo ""

# Verificar se o certificado já foi instalado
if [ ! -f "/srv/nginx/ssl/fullchain.pem" ]; then
    echo "ERRO: Certificado SSL não encontrado!"
    echo "Execute primeiro: /srv/install-ssl.sh"
    exit 1
fi

# Tornar scripts executáveis
chmod +x /srv/renew-ssl.sh
chmod +x /srv/post-renew.sh
chmod +x /srv/install-ssl.sh

echo "Configurando cronjob para renovação automática..."

# Criar arquivo cron
cat > "$CRON_FILE" << 'EOF'
# Renovação automática de certificado SSL para NutriDiario
# Executa diariamente às 3:30 AM
30 3 * * * root /srv/renew-ssl.sh >> /srv/nginx/logs/ssl-renewal.log 2>&1
EOF

# Ajustar permissões do arquivo cron
chmod 644 "$CRON_FILE"

echo ""
echo "=== Configuração concluída! ==="
echo ""
echo "Detalhes da configuração:"
echo "  - Renovação automática agendada para 3:30 AM diariamente"
echo "  - Logs em: /srv/nginx/logs/ssl-renewal.log"
echo "  - Certificado é renovado automaticamente 30 dias antes do vencimento"
echo "  - Nginx é recarregado automaticamente após renovação (SEM downtime)"
echo ""
echo "Para testar a renovação manualmente, execute:"
echo "  /srv/renew-ssl.sh"
echo ""
echo "Para ver quando o certificado expira:"
echo "  certbot certificates"
echo ""
