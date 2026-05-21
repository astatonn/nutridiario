#!/bin/bash

# Script de Backup do Formulário A Dieta Que Funciona
# Executa backup a cada 5 dias e remove backups com mais de 30 dias

# Configurações
BACKUP_DIR="/srv/backups/formulario"
LOG_FILE="/srv/logs/backup-formulario.log"
RETENTION_DAYS=30
DATE=$(date +%Y%m%d_%H%M%S)

# Criar diretórios se não existirem
mkdir -p "$BACKUP_DIR"
mkdir -p "$(dirname "$LOG_FILE")"

# Função de log
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

log "========================================="
log "Iniciando backup do formulário"
log "========================================="

# 1. Backup dos arquivos do Laravel (views, controllers, js, css)
log "Criando backup dos arquivos do formulário..."

BACKUP_FILE="$BACKUP_DIR/formulario_backup_$DATE.tar.gz"

tar -czf "$BACKUP_FILE" \
    -C /srv/laravel/app \
    resources/views/adietaquefunciona.blade.php \
    public/js/adietaquefunciona.js \
    public/css/adietaquefunciona.css \
    app/Http/Controllers/PlanoAlimentarController.php \
    app/Services/SupabaseService.php \
    2>> "$LOG_FILE"

if [ $? -eq 0 ]; then
    BACKUP_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    log "✓ Backup criado com sucesso: $BACKUP_FILE ($BACKUP_SIZE)"
else
    log "✗ Erro ao criar backup dos arquivos"
    exit 1
fi

# 2. Backup da configuração do ambiente
log "Criando backup das configurações..."

ENV_BACKUP_FILE="$BACKUP_DIR/env_backup_$DATE.tar.gz"

tar -czf "$ENV_BACKUP_FILE" \
    -C /srv/laravel/app \
    .env \
    2>> "$LOG_FILE"

if [ $? -eq 0 ]; then
    log "✓ Backup das configurações criado com sucesso"
else
    log "⚠ Aviso: Não foi possível fazer backup do .env"
fi

# 3. Criar um arquivo de metadados do backup
cat > "$BACKUP_DIR/backup_$DATE.info" <<EOF
Data do Backup: $(date '+%Y-%m-%d %H:%M:%S')
Arquivos incluídos:
- adietaquefunciona.blade.php
- adietaquefunciona.js
- adietaquefunciona.css
- PlanoAlimentarController.php
- SupabaseService.php
- .env (configurações)

Tamanho total: $(du -h "$BACKUP_FILE" | cut -f1)
EOF

log "✓ Arquivo de metadados criado"

# 4. Listar backups existentes
log "Backups existentes:"
ls -lh "$BACKUP_DIR"/*.tar.gz 2>/dev/null | awk '{print $9, "-", $5}' | tee -a "$LOG_FILE"

# 5. Remover backups antigos (mais de 30 dias)
log "Removendo backups com mais de $RETENTION_DAYS dias..."

DELETED_COUNT=0
while IFS= read -r old_backup; do
    if [ -f "$old_backup" ]; then
        rm -f "$old_backup"
        log "  ✓ Removido: $(basename "$old_backup")"
        DELETED_COUNT=$((DELETED_COUNT + 1))
    fi
done < <(find "$BACKUP_DIR" -name "*.tar.gz" -type f -mtime +$RETENTION_DAYS)

# Remover também os arquivos .info antigos
find "$BACKUP_DIR" -name "*.info" -type f -mtime +$RETENTION_DAYS -delete

if [ $DELETED_COUNT -eq 0 ]; then
    log "  ℹ Nenhum backup antigo encontrado para remoção"
else
    log "  ✓ Total de backups removidos: $DELETED_COUNT"
fi

# 6. Estatísticas finais
TOTAL_BACKUPS=$(ls -1 "$BACKUP_DIR"/*.tar.gz 2>/dev/null | wc -l)
TOTAL_SIZE=$(du -sh "$BACKUP_DIR" | cut -f1)

log "========================================="
log "Backup concluído com sucesso!"
log "Total de backups mantidos: $TOTAL_BACKUPS"
log "Espaço total utilizado: $TOTAL_SIZE"
log "========================================="

exit 0
