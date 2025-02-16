# Sistema de Gestão Empresarial

Sistema desenvolvido para gestão de grupos econômicos, bandeiras, unidades e colaboradores, com recursos avançados de auditoria e relatórios.

## 🚀 Tecnologias Utilizadas

### Backend
- **Laravel 11**: Framework PHP com as mais recentes features
- **PHP 8.4**: Última versão estável do PHP
- **MySQL**: Banco de dados relacional
- **Redis**: Cache e filas
- **Mailpit**: Servidor SMTP para testes de email

### Frontend
- **Vue.js 3**: Framework JavaScript progressivo
- **TypeScript**: Tipagem estática para JavaScript
- **Inertia.js**: Construção de SPA sem necessidade de API

### Ambiente de Desenvolvimento
- **Docker + Laravel Sail**: Containerização do ambiente
- **Dev Container**: Configuração consistente do ambiente de desenvolvimento
- **Git**: Controle de versão

## ✨ Funcionalidades

### CRUDs Completos
- Grupos Econômicos
- Bandeiras
- Unidades
- Colaboradores

Cada CRUD inclui:
- Validações completas (front e backend)
- Testes unitários e de integração
- Sistema de auditoria para tracking de mudanças

### Sistema de Relatórios
- Exportação assíncrona para Excel
- Processamento em background via Redis Queue
- Notificação por email quando relatório está pronto

### Auditoria
- Registro de todas alterações nas entidades
- Histórico de modificações com usuário e timestamp
- Rastreamento de campos alterados

## 🔧 Configuração do Ambiente

### Pré-requisitos
- Docker
- Git

### Instalação

1. Clone o repositório
```bash
git clone https://github.com/mendes-gmcb/vochtech.git
cd vochtech
```

2. Copie o arquivo de ambiente
```bash
cp .env.example .env
```

3. Inicie os containers
```bash
./vendor/bin/sail up -d
```

4. Instale as dependências
```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

5. Gere a chave da aplicação
```bash
./vendor/bin/sail artisan key:generate
```

6. Execute as migrações
```bash
./vendor/bin/sail artisan migrate --seed
```

7. Crie o link simbólico para storage
```bash
./vendor/bin/sail artisan storage:link
```

### Iniciando Workers

Para processar as filas (necessário para relatórios):
```bash
./vendor/bin/sail artisan queue:work redis --queue=reports
```

Para monitoramento das filas (opcional):
```bash
./vendor/bin/sail artisan horizon
```

## 📦 Estrutura do Projeto

```
├── app
│   ├── Exports             # Exportar Relatórios 
│   ├── Http
│   │   ├── Controllers      # Controllers
│   │   └── Requests        # Form Requests para validação
│   ├── Jobs                # Jobs assíncronos
│   ├── Models              # Models do Eloquent
│   ├── Repositories        # Repositories para abstrair as models e facilitar a criação de mocks para os testes
├── database
│   ├── migrations         # Migrations
│   └── seeders           # Seeders
├── resources
│   ├── js                # Código Vue.js + TypeScript
│   └── views            # Views do Inertia
└── tests                # Testes automatizados
```

## 🧪 Testes

Execute os testes com:
```bash
./vendor/bin/sail artisan test
```

Para testes específicos:
```bash
./vendor/bin/sail artisan test --filter=NomeDoTeste
```

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.