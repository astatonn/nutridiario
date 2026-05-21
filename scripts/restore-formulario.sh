#!/bin/bash

# Script de Restauração do Formulário A Dieta Que Funciona
# Restaura arquivos a partir de um backup específico

BACKUP_DIR="/srv/backups/formulario"
LOG_FILE="/srv/logs/restore-formulario.log"

# Função de log
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Função de ajuda
show_help() {
    cat << EOF
Uso: $0 [OPÇÕES]

Restaura o formulário A Dieta Que Funciona a partir de um backup.

OPÇÕES:
    -l, --list              Lista todos os backups disponíveis
    -r, --restore <file>    Restaura um backup específico
    -h, --help              Mostra esta ajuda

EXEMPLOS:
    $0 --list
    $0 --restore formulario_backup_20260103_121007.tar.gz

EOF
    exit 0
}

# Função para listar backups
list_backups() {
    echo "========================================="
    echo "Backups disponíveis:"
    echo "========================================="

    if [ ! -d "$BACKUP_DIR" ]; then
        echo "⚠ Diretório de backup não encontrado: $BACKUP_DIR"
        exit 1
    fi

    COUNT=0
    for backup in "$BACKUP_DIR"/formulario_backup_*.tar.gz; do
        if [ -f "$backup" ]; then
            COUNT=$((COUNT + 1))
            FILENAME=$(basename "$backup")
            SIZE=$(du -h "$backup" | cut -f1)
            DATE=$(stat -c %y "$backup" | cut -d'.' -f1)

            echo "$COUNT. $FILENAME"
            echo "   Tamanho: $SIZE"
            echo "   Data: $DATE"

            # Mostrar metadados se existir
            INFO_FILE="${backup%.tar.gz}.info"
            if [ -f "$BACKUP_DIR/backup_${FILENAME#formulario_backup_}.info" ]; then
                echo "   ℹ Arquivo de informações disponível"
            fi
            echo ""
        fi
    done

    if [ $COUNT -eq 0 ]; then
        echo "⚠ Nenhum backup encontrado"
        exit 1
    fi

    echo "Total de backups: $COUNT"
    exit 0
}

# Função para restaurar backup
restore_backup() {
    local BACKUP_FILE="$1"

    log "========================================="
    log "Iniciando restauração do formulário"
    log "========================================="

    # Verificar se o arquivo existe
    if [ ! -f "$BACKUP_DIR/$BACKUP_FILE" ]; then
        log "✗ Erro: Arquivo de backup não encontrado: $BACKUP_FILE"
        exit 1
    fi

    log "Arquivo de backup: $BACKUP_FILE"

    # Criar backup dos arquivos atuais antes de restaurar
    TEMP_BACKUP="/tmp/pre_restore_backup_$(date +%Y%m%d_%H%M%S).tar.gz"
    log "Criando backup de segurança dos arquivos atuais..."

    tar -czf "$TEMP_BACKUP" \
        -C /srv/laravel/app \
        resources/views/adietaquefunciona.blade.php \
        public/js/adietaquefunciona.js \
        public/css/adietaquefunciona.css \
        app/Http/Controllers/PlanoAlimentarController.php \
        app/Services/SupabaseService.php \
        2>> "$LOG_FILE"

    if [ $? -eq 0 ]; then
        log "✓ Backup de segurança criado: $TEMP_BACKUP"
    else
        log "⚠ Aviso: Não foi possível criar backup de segurança"
    fi

    # Restaurar arquivos
    log "Restaurando arquivos do backup..."

    tar -xzf "$BACKUP_DIR/$BACKUP_FILE" \
        -C /srv/laravel/app \
        2>> "$LOG_FILE"

    if [ $? -eq 0 ]; then
        log "✓ Arquivos restaurados com sucesso!"

        # Verificar permissões
        log "Ajustando permissões dos arquivos..."
        chown -R www-data:www-data /srv/laravel/app/resources/views/
        chown -R www-data:www-data /srv/laravel/app/public/
        chmod 644 /srv/laravel/app/resources/views/adietaquefunciona.blade.php
        chmod 644 /srv/laravel/app/public/js/adietaquefunciona.js
        chmod 644 /srv/laravel/app/public/css/adietaquefunciona.css

        log "✓ Permissões ajustadas"

        # Reiniciar containers se necessário
        if command -v docker &> /dev/null; then
            log "Reiniciando containers Docker..."
            docker restart nutridiario-app 2>> "$LOG_FILE"
            docker restart nutridiario-nginx 2>> "$LOG_FILE"
            log "✓ Containers reiniciados"
        fi

        log "========================================="
        log "Restauração concluída com sucesso!"
        log "Backup de segurança salvo em: $TEMP_BACKUP"
        log "========================================="

    else
        log "✗ Erro ao restaurar arquivos"
        log "Backup de segurança disponível em: $TEMP_BACKUP"
        exit 1
    fi
}

# Processar argumentos
case "$1" in
    -l|--list)
        list_backups
        ;;
    -r|--restore)
        if [ -z "$2" ]; then
            echo "Erro: Nome do arquivo de backup não especificado"
            echo "Use: $0 --restore <arquivo>"
            exit 1
        fi
        restore_backup "$2"
        ;;
    -h|--help|"")
        show_help
        ;;
    *)
        echo "Opção inválida: $1"
        show_help
        ;;
esac

exit 0
