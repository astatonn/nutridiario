# Sistema de Backup e Restauração - Formulário A Dieta Que Funciona

## 📋 Visão Geral

Sistema automático de backup que:
- **Cria backups a cada 5 dias** às 2h da manhã
- **Remove backups antigos** (mais de 30 dias) automaticamente
- **Inclui todos os arquivos** do formulário (blade, js, css, controllers)
- **Mantém configurações** (.env)

## 📁 Estrutura de Arquivos

```
/srv/
├── scripts/
│   ├── backup-formulario.sh      # Script de backup automático
│   ├── restore-formulario.sh     # Script de restauração manual
│   └── README-BACKUP.md           # Este arquivo
├── backups/
│   └── formulario/                # Backups armazenados aqui
│       ├── formulario_backup_YYYYMMDD_HHMMSS.tar.gz
│       ├── env_backup_YYYYMMDD_HHMMSS.tar.gz
│       └── backup_YYYYMMDD_HHMMSS.info
└── logs/
    ├── backup-formulario.log      # Log de backups
    └── restore-formulario.log     # Log de restaurações
```

## 🔄 Backup Automático

### Como funciona

O backup é executado automaticamente via cron job:
- **Frequência**: A cada 5 dias às 2h da manhã
- **Retenção**: 30 dias (backups mais antigos são apagados automaticamente)
- **Arquivo cron**: `/etc/cron.d/formulario-backup`

### Arquivos incluídos no backup

1. **View (Blade Template)**
   - `resources/views/adietaquefunciona.blade.php`

2. **JavaScript**
   - `public/js/adietaquefunciona.js`

3. **CSS**
   - `public/css/adietaquefunciona.css`

4. **Controllers**
   - `app/Http/Controllers/PlanoAlimentarController.php`

5. **Services**
   - `app/Services/SupabaseService.php`

6. **Configurações**
   - `.env` (backup separado)

### Executar backup manualmente

```bash
/srv/scripts/backup-formulario.sh
```

### Verificar logs de backup

```bash
tail -f /srv/logs/backup-formulario.log
```

## 🔧 Restauração de Backup

### Listar backups disponíveis

```bash
/srv/scripts/restore-formulario.sh --list
```

Exemplo de saída:
```
=========================================
Backups disponíveis:
=========================================
1. formulario_backup_20260103_121007.tar.gz
   Tamanho: 28K
   Data: 2026-01-03 12:10:07
   ℹ Arquivo de informações disponível

Total de backups: 1
```

### Restaurar um backup específico

```bash
/srv/scripts/restore-formulario.sh --restore formulario_backup_20260103_121007.tar.gz
```

**IMPORTANTE**: O script:
1. Cria um backup de segurança dos arquivos atuais antes de restaurar
2. Restaura os arquivos do backup selecionado
3. Ajusta as permissões automaticamente
4. Reinicia os containers Docker (se disponível)

### Verificar logs de restauração

```bash
tail -f /srv/logs/restore-formulario.log
```

## 📊 Monitoramento

### Verificar status do cron

```bash
service cron status
```

### Listar próximas execuções

```bash
grep formulario /etc/cron.d/formulario-backup
```

### Verificar espaço em disco usado pelos backups

```bash
du -sh /srv/backups/formulario/
```

### Ver todos os backups com detalhes

```bash
ls -lh /srv/backups/formulario/
```

## ⚠️ Avisos Importantes

1. **Backups não incluem banco de dados**: O sistema faz backup apenas dos arquivos do formulário, não dos dados do Supabase.

2. **Retenção de 30 dias**: Backups mais antigos são apagados automaticamente. Se precisar manter um backup por mais tempo, copie-o para outro local.

3. **Backup antes de restaurar**: O script de restauração cria um backup de segurança em `/tmp/` antes de restaurar. Este backup é temporário.

4. **Reinício de containers**: A restauração reinicia automaticamente os containers Docker.

## 🛠️ Manutenção

### Alterar frequência de backup

Editar o arquivo:
```bash
nano /etc/cron.d/formulario-backup
```

Formato cron: `minuto hora dia mês dia-da-semana`
- A cada 5 dias: `0 2 */5 * *`
- Diário: `0 2 * * *`
- Semanal (domingo): `0 2 * * 0`

### Alterar período de retenção

Editar o script:
```bash
nano /srv/scripts/backup-formulario.sh
```

Alterar a linha:
```bash
RETENTION_DAYS=30  # Alterar para o número de dias desejado
```

### Desativar backup automático

```bash
rm /etc/cron.d/formulario-backup
service cron reload
```

### Reativar backup automático

O arquivo cron já está criado em `/etc/cron.d/formulario-backup`.
Se foi removido, basta recriar ou executar:

```bash
service cron reload
```

## 📞 Suporte

Para mais informações sobre o sistema de backup, consulte os logs:
- Backup: `/srv/logs/backup-formulario.log`
- Restauração: `/srv/logs/restore-formulario.log`
- Sistema: `/var/log/syslog` (procurar por "formulario")

---
**Última atualização**: 2026-01-03
**Versão**: 1.0
