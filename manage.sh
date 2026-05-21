#!/bin/bash

# Script de gerenciamento do NutriDiario
# Uso: ./manage.sh [comando]

set -e

COMPOSE_FILE="/srv/docker-compose.yml"
ENV_FILE="/srv/.env"

cd /srv

case "$1" in
    start)
        echo "Iniciando containers..."
        docker compose up -d
        echo "Containers iniciados com sucesso!"
        docker compose ps
        ;;

    stop)
        echo "Parando containers..."
        docker compose down
        echo "Containers parados com sucesso!"
        ;;

    restart)
        echo "Reiniciando containers..."
        docker compose restart
        echo "Containers reiniciados com sucesso!"
        ;;

    logs)
        if [ -z "$2" ]; then
            docker compose logs -f
        else
            docker compose logs -f "$2"
        fi
        ;;

    status)
        echo "Status dos containers:"
        docker compose ps
        echo ""
        echo "Uso de recursos:"
        docker stats --no-stream
        ;;

    shell-app)
        echo "Abrindo shell no container Laravel..."
        docker exec -it nutridiario-app bash
        ;;

    shell-db)
        echo "Abrindo shell no PostgreSQL..."
        docker exec -it nutridiario-db psql -U nutridiario_user -d nutridiario
        ;;

    artisan)
        if [ -z "$2" ]; then
            echo "Uso: ./manage.sh artisan [comando]"
            exit 1
        fi
        shift
        docker exec -it nutridiario-app php artisan "$@"
        ;;

    backup-db)
        BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
        echo "Criando backup do banco de dados..."
        docker exec nutridiario-db pg_dump -U nutridiario_user nutridiario > "/srv/$BACKUP_FILE"
        echo "Backup criado: /srv/$BACKUP_FILE"
        ;;

    restore-db)
        if [ -z "$2" ]; then
            echo "Uso: ./manage.sh restore-db [arquivo.sql]"
            exit 1
        fi
        echo "Restaurando backup do banco de dados..."
        docker exec -i nutridiario-db psql -U nutridiario_user nutridiario < "$2"
        echo "Backup restaurado com sucesso!"
        ;;

    update)
        echo "Atualizando containers..."
        docker compose pull
        docker compose up -d --build
        echo "Containers atualizados com sucesso!"
        ;;

    clean)
        echo "Limpando containers e imagens não utilizados..."
        docker container prune -f
        docker image prune -a -f
        echo "Limpeza concluída!"
        ;;

    ssl-install)
        echo "Instalando certificado SSL..."
        /srv/install-ssl.sh
        ;;

    ssl-setup-auto-renew)
        echo "Configurando renovação automática..."
        /srv/setup-auto-renew.sh
        ;;

    ssl-renew)
        echo "Renovando certificado SSL manualmente..."
        /srv/renew-ssl.sh
        ;;

    ssl-status)
        echo "=== Status do Certificado SSL ==="
        certbot certificates
        ;;

    security-check)
        echo "=== Checklist de Segurança ==="
        echo ""
        echo "1. Verificando permissões do .env:"
        ls -l /srv/.env
        echo ""
        echo "2. Verificando UFW:"
        ufw status
        echo ""
        echo "3. Verificando portas expostas:"
        netstat -tlnp | grep -E ':(80|443|22) '
        echo ""
        echo "4. Verificando containers em execução:"
        docker compose ps
        echo ""
        echo "5. Verificando se PostgreSQL está exposto:"
        if netstat -tlnp | grep -q ':5432 '; then
            echo "AVISO: PostgreSQL está exposto externamente!"
        else
            echo "OK: PostgreSQL não está exposto externamente"
        fi
        ;;

    *)
        echo "Script de gerenciamento do NutriDiario"
        echo ""
        echo "Uso: ./manage.sh [comando]"
        echo ""
        echo "Comandos disponíveis:"
        echo ""
        echo "  Gerenciamento de Containers:"
        echo "    start                 - Iniciar todos os containers"
        echo "    stop                  - Parar todos os containers"
        echo "    restart               - Reiniciar todos os containers"
        echo "    status                - Ver status dos containers"
        echo "    logs [serviço]        - Ver logs (nginx|laravel|postgresql)"
        echo "    update                - Atualizar imagens dos containers"
        echo "    clean                 - Limpar containers e imagens não utilizados"
        echo ""
        echo "  Laravel:"
        echo "    shell-app             - Abrir shell no container Laravel"
        echo "    artisan [cmd]         - Executar comando artisan"
        echo ""
        echo "  Banco de Dados:"
        echo "    shell-db              - Abrir shell no PostgreSQL"
        echo "    backup-db             - Criar backup do banco de dados"
        echo "    restore-db [file]     - Restaurar backup do banco de dados"
        echo ""
        echo "  SSL/HTTPS:"
        echo "    ssl-install           - Instalar certificado SSL (primeira vez)"
        echo "    ssl-setup-auto-renew  - Configurar renovação automática"
        echo "    ssl-renew             - Renovar certificado manualmente"
        echo "    ssl-status            - Ver status do certificado"
        echo ""
        echo "  Segurança:"
        echo "    security-check        - Verificar configurações de segurança"
        echo ""
        ;;
esac
