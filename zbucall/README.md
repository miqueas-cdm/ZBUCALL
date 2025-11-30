# Portal do Associados  

Sistema completo e moderno de portal do colaborador desenvolvido com PHP, MySQL, HTML, TailwindCSS e JavaScript.

## 📋 Características

- ✅ **Dashboard** - Tela inicial com estatísticas e atalhos rápidos
- ✅ **Perfil do associado** - Visualização de dados pessoais e profissionais
- ✅ **Clube de Vantagens** - Consulta de benefícios ativos (VT, VA, VR, Plano de Saúde, etc.)
- ✅ **Documentos** - Upload e download de documentos
- ✅ **Comunicados** - Notícias e avisos da empresa
- ✅ **Solicitações** - Envio e acompanhamento de solicitações (férias, RH, etc.)

## 🎨 Recursos Extras

- 🌓 **Tema Claro/Escuro** - Alternância entre temas com salvamento de preferência
- 🔔 **Sistema de Notificações** - Notificações em tempo real
- 🔍 **Busca Global** - Pesquisa em todos os módulos
- 🍞 **Breadcrumbs** - Navegação contextual
- ⚡ **Loading Skeletons** - Animações de carregamento
- 📱 **Design Responsivo** - Totalmente adaptável a dispositivos móveis
- 🎭 **Animações Suaves** - Transições e efeitos visuais elegantes

## 📁 Estrutura do Projeto

```
zbucall/
├── api/                          # API endpoints PHP
│   ├── auth.php                  # Autenticação (login/logout)
│   ├── dashboard.php             # Dados do dashboard
│   ├── profile.php               # Perfil do colaborador
│   ├── benefits.php              # Benefícios
│   ├── payslips.php              # Holerites
│   ├── time-records.php          # Registro de ponto
│   ├── documents.php             # Gerenciamento de documentos
│   ├── communications.php        # Comunicados
│   ├── requests.php              # Solicitações
│   ├── notifications.php         # Notificações
│   └── search.php                # Busca global
├── assets/
│   ├── css/
│   │   └── styles.css            # Estilos customizados
│   └── js/
│       ├── main.js               # JavaScript principal
│       └── components.js         # Componentes reutilizáveis
├── components/                   # Componentes PHP reutilizáveis
│   ├── header.php                # Cabeçalho
│   ├── sidebar.php               # Menu lateral
│   └── breadcrumbs.php           # Navegação breadcrumb
├── config/                       # Configurações
│   ├── database.php              # Conexão com banco de dados
│   └── session.php               # Gerenciamento de sessões
├── uploads/                      # Diretório para uploads
│   └── documents/                # Documentos dos colaboradores
├── index.php                     # Página de login
├── dashboard.php                 # Dashboard principal
├── profile.php                   # Perfil do colaborador
├── benefits.php                  # Benefícios
├── payslips.php                  # Holerites
├── time-records.php              # Ponto eletrônico
├── documents.php                 # Documentos
├── communications.php            # Comunicados
├── requests.php                  # Solicitações
├── schema.sql                    # Schema do banco de dados
└── README.md                     # Este arquivo
```

## ⚙️ Instalação

### Pré-requisitos

- **PHP** 7.4 ou superior
- **MySQL** 5.7 ou superior (ou MariaDB)
- **Servidor Web** (Apache, Nginx, ou PHP built-in server)
- **Extensões PHP**: PDO, PDO_MySQL

### Passo a Passo

#### 1. Configurar o Banco de Dados

```bash
# Crie o banco de dados
mysql -u root -p
CREATE DATABASE portal_colaborador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Importe o schema
mysql -u root -p portal_colaborador < schema.sql
```

#### 2. Configurar a Conexão com o Banco

Edite o arquivo `config/database.php` e ajuste as credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portal_colaborador');
define('DB_USER', 'root');
define('DB_PASS', '');
```

#### 3. Criar Diretórios de Upload

```bash
mkdir -p uploads/documents
chmod 755 uploads
chmod 755 uploads/documents
```

#### 4. Iniciar o Servidor

##### Opção 1: Servidor Built-in do PHP (Desenvolvimento)

```bash
cd C:\Users\zbucall
php -S localhost:8000
```

Acesse: http://localhost:8000

##### Opção 2: XAMPP/WAMP

1. Copie a pasta `miqueias` para `C:\xampp\htdocs\`
2. Acesse: http://localhost/zbucall

## 🔐 Credenciais de Teste

O banco de dados vem com dados de exemplo:

**Associado 1:**
- **Email:** joao.silva@empresa.com
- **Senha:** senha123

**Associado 2:**
- **Email:** maria.oliveira@empresa.com
- **Senha:** senha123

> **Nota:** As senhas estão criptografadas com `password_hash()` do PHP.

## 🗄️ Estrutura do Banco de Dados

### Tabelas Principais

- **employees** - Dados dos colaboradores
- **benefits** - Benefícios dos colaboradores
- **payslips** - Holerites mensais
- **time_records** - Registros de ponto
- **documents** - Documentos e arquivos
- **communications** - Comunicados internos
- **requests** - Solicitações dos colaboradores
- **notifications** - Notificações do sistema

## 🎨 Personalização de Tema

O portal suporta temas claro e escuro. A preferência é salva no `localStorage` do navegador.

### Cores Principais

**Tema Claro:**
- Primary: `#1f4e8c` (Azul corporativo)
- Background: `#ffffff`
- Text: `#1f2937`

**Tema Escuro:**
- Primary: `#3b82f6` (Azul claro)
- Background: `#111827`
- Text: `#f9fafb`

## 📱 Responsividade

O portal é totalmente responsivo e funciona perfeitamente em:
- 📱 Smartphones (320px+)
- 📱 Tablets (768px+)
- 💻 Desktops (1024px+)
- 🖥️ Telas grandes (1920px+)

## 🔒 Segurança

### Medidas Implementadas

1. **Senhas Criptografadas** - Uso de `password_hash()` e `password_verify()`
2. **Prepared Statements** - Proteção contra SQL Injection
3. **Sessões Seguras** - Gerenciamento adequado de sessões PHP
4. **Validação de Entrada** - Sanitização de dados do usuário
5. **Autenticação Obrigatória** - Verificação de login em todas as páginas

### Recomendações para Produção

- [ ] Implementar HTTPS
- [ ] Configurar tokens CSRF
- [ ] Adicionar rate limiting no login
- [ ] Implementar logs de auditoria
- [ ] Configurar backup automático do banco de dados
- [ ] Alterar as credenciais padrão do banco de dados

## 🚀 Funcionalidades Futuras

- [ ] Sistema de chat interno
- [ ] Integração com API de ponto eletrônico
- [ ] Geração de PDF para holerites
- [ ] Sistema de aprovação multinível para solicitações
- [ ] Dashboard administrativo
- [ ] Relatórios e analytics
- [ ] Integração com e-mail (notificações)
- [ ] App mobile (React Native / Flutter)

## 🐛 Solução de Problemas

### Erro de Conexão com Banco de Dados

```
Database connection failed: SQLSTATE[HY000] [1045]
```

**Solução:** Verifique as credenciais em `config/database.php`

### Erro de Permissão de Upload

```
Warning: move_uploaded_file(): failed to open stream
```

**Solução:** Verifique as permissões da pasta `uploads/`
```bash
chmod -R 755 uploads/
```

### Sessão Não Persiste

**Solução:** Verifique se o PHP tem permissão para criar arquivos de sessão:
```php
// Em config/session.php, adicione:
ini_set('session.save_path', __DIR__ . '/../sessions');
```

## 📄 Licença

Este projeto é de código aberto e está disponível sob a licença MIT.

## 👨‍💻 Desenvolvido por

Portal do Colaborador - Sistema de Gestão 

**Última atualização:** Novembro 2025

Para suporte ou dúvidas, entre em contato com o departamento de TI.

![CANVA-ZBUCAL](https://github.com/user-attachments/assets/a44a6b67-3336-4540-aa52-7729c2b37756)


