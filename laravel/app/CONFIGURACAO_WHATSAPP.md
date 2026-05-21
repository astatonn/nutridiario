# Configuração dos Números de WhatsApp

Este documento explica como configurar os números de WhatsApp para redirecionamento após o preenchimento do formulário.

## 📍 Localização do Código

O código de configuração está no arquivo:
```
/srv/laravel/app/public/js/adietaquefunciona.js
```

Procure pelas linhas que contêm `whatsappConfig`.

## 🔧 Como Configurar

### 1. Paciente SEM Dieta

**Quando usar:** Paciente responde "NÃO" na pergunta "Você tem uma dieta?"

**Configuração atual:**
```javascript
sem_dieta: {
    numero: '5511999999999', // SUBSTITUIR PELO NÚMERO REAL
    mensagem: 'Oii! Preenchi o forms e vim garantir minha dieta e meu acompanhamento de 30 dias :)'
}
```

**Para configurar:**
1. Substitua `5511999999999` pelo número real (incluindo código do país e DDD)
2. Formato: `55` (Brasil) + `11` (DDD) + `999999999` (número)
3. Exemplo: `5511987654321`

### 2. Paciente COM Dieta

**Quando usar:** Paciente responde "SIM" na pergunta "Você tem uma dieta?"

**Configuração atual:**
```javascript
com_dieta: {
    numero: '5511988888888', // SUBSTITUIR PELO NÚMERO REAL
    mensagem: 'Oii! Prenchi o forms e vim garantir meu acompanhamento de 30 dias :)'
}
```

**Para configurar:**
1. Substitua `5511988888888` pelo número real (incluindo código do país e DDD)
2. Formato: `55` (Brasil) + `11` (DDD) + `988888888` (número)
3. Exemplo: `5511912345678`

## 📝 Exemplo de Configuração

```javascript
const whatsappConfig = {
    // Paciente SEM dieta
    sem_dieta: {
        numero: '5511987654321',
        mensagem: 'Oii! Preenchi o forms e vim garantir minha dieta e meu acompanhamento de 30 dias :)'
    },
    // Paciente COM dieta
    com_dieta: {
        numero: '5511912345678',
        mensagem: 'Oii! Prenchi o forms e vim garantir meu acompanhamento de 30 dias :)'
    }
};
```

## 🔄 Como Aplicar as Mudanças

Após editar o arquivo JavaScript:

1. **Limpar cache do navegador** (Ctrl+Shift+Delete)
2. **Recarregar a página com cache limpo** (Ctrl+F5)
3. **Ou reiniciar o container:**
   ```bash
   docker restart nutridiario-app
   ```

## ✅ Como Testar

1. Acesse `/planoalimentar`
2. Preencha o formulário
3. Na primeira pergunta "Você tem uma dieta?":
   - Responda **NÃO** → Deve redirecionar para o primeiro número
   - Responda **SIM** → Deve redirecionar para o segundo número
4. Finalize o formulário
5. Verifique se o WhatsApp abre com a mensagem correta

## 📱 Formato do Link WhatsApp

O sistema gera links no formato:
```
https://wa.me/5511999999999?text=Oii!%20Preenchi%20o%20forms...
```

## ⚠️ Notas Importantes

- **Não use espaços, hífens ou parênteses** no número
- **Use apenas números**, incluindo código do país (55 para Brasil)
- **Exemplo correto:** `5511987654321`
- **Exemplo incorreto:** `+55 (11) 98765-4321`
- As mensagens são automaticamente codificadas (URL encode)

## 🎯 Fluxos de Redirecionamento

```
┌─────────────────────────┐
│ Usuário preenche forms  │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐
│ Pergunta: Tem dieta?    │
└───────────┬─────────────┘
            │
      ┌─────┴─────┐
      │           │
   SIM│           │NÃO
      ▼           ▼
┌─────────┐   ┌──────────┐
│ Número  │   │ Número   │
│ COM     │   │ SEM      │
│ dieta   │   │ dieta    │
└─────────┘   └──────────┘
      │           │
      └─────┬─────┘
            ▼
┌─────────────────────────┐
│ Abre WhatsApp com       │
│ mensagem personalizada  │
└─────────────────────────┘
```

## 🆘 Suporte

Se houver problemas:
1. Verifique o console do navegador (F12)
2. Confirme que os números estão no formato correto
3. Teste manualmente o link: `https://wa.me/5511999999999`
