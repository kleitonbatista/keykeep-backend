📑 Manual de Implantação e Modelagem Backend — KeyKeep API
1. Estrutura de Arquivos da Infraestrutura
Na raiz do projeto (/home/kleiton/keykeep/), os seguintes arquivos base foram configurados para orquestrar o ambiente Docker Multi-Container customizado:

📄 Dockerfile
Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
📄 docker-compose.yml
YAML
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: keykeep-app
    container_name: keykeep-app
    restart: unless-stopped
    tty: true
    user: "1000:1000"
    environment:
      SERVICE_NAME: app
    volumes:
      - .:/var/www
    networks:
      - keykeep-network

  web:
    image: nginx:alpine
    container_name: keykeep-web
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - .:/var/www
      - ./docker/nginx:/etc/nginx/conf.d/
    networks:
      - keykeep-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: keykeep-db
    restart: unless-stopped
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: keykeep_db
      MYSQL_ROOT_PASSWORD: root_password_secure
      MYSQL_USER: keykeep_user
      MYSQL_PASSWORD: keykeep_password_secure
    volumes:
      - keykeep-db-data:/var/lib/mysql
    networks:
      - keykeep-network

networks:
  keykeep-network:
    driver: bridge

volumes:
  keykeep-db-data:
    driver: local
📄 docker/nginx/default.conf
Nginx
server {
    listen 80;
    index index.php index.html;
    root /var/www/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
2. Comandos de Inicialização e Alinhamento de Permissões (WSL2)
Para subir os containers e corrigir os privilégios de escrita do VS Code e do Laravel Engine, execute em sequência no terminal do Ubuntu:

Bash
# 1. Subir os containers em segundo plano
docker-compose up -d

# 2. Devolver a posse dos arquivos criados ao seu usuário local do WSL
sudo chown -R $USER:$USER .
find . -type f -exec chmod 664 {} +
find . -type d -exec chmod 775 {} +

# 3. Garantir as permissões internas para o motor do Laravel gerar caches e logs
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
3. Configuração do Ambiente Laravel (.env)
As variáveis de conexão do banco de dados no arquivo .env foram configuradas apontando para o container isolado do MySQL:

Snippet de código
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=keykeep_db
DB_USERNAME=keykeep_user
DB_PASSWORD=keykeep_password_secure
4. Estrutura de Rotas da API
O arquivo de inicialização bootstrap/app.php foi modificado para ler o arquivo de rotas da API:

PHP
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
E o arquivo routes/api.php foi instanciado com o endpoint de validação técnica:

PHP
<?php

use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json([
        'app_name' => 'KeyKeep API',
        'version' => '1.0.0',
        'status' => 'Online e Operante',
        'message' => 'Olá, Mundo! O backend em Laravel 12 está pronto para o React.'
    ], 200);
});
5. Modelagem Relacional do Banco de Dados (Dicionário de Dados)
Abaixo está o mapeamento das tabelas que criamos nas migrations para suportar as regras de negócio de criptografia, categorização e segurança.

📊 Tabela: users
Responsável pela autenticação, controle de ativação da conta e segurança multifator (2FA).

id (BIGINT, Primary Key, Auto-increment)

name (VARCHAR(255))

email (VARCHAR(255), Unique) — Login do usuário.

password (VARCHAR(255)) — Hash seguro da senha master.

is_active (BOOLEAN, Default: false) — Define se a conta foi confirmada por e-mail.

activation_token (VARCHAR(64), Nullable) — Token enviado no e-mail de ativação.

two_factor_code (VARCHAR(6), Nullable) — Token temporário de login.

two_factor_expires_at (TIMESTAMP, Nullable) — Validade do token 2FA.

timestamps (created_at e updated_at)

📊 Tabela: categories
Catálogo estático de tipos de contas para renderização dinâmica de ícones no frontend React.

id (BIGINT, Primary Key, Auto-increment)

name (VARCHAR(100), Unique) — Ex: "E-mail", "Redes Sociais", "Streaming", "Bancos".

icon_identifier (VARCHAR(100)) — String que mapeia o ícone do pacote Lucide React ou FontAwesome.

timestamps (created_at e updated_at)

📊 Tabela: credentials
Onde residem os registros das contas salvas pelos utilizadores, utilizando forte isolamento por chave estrangeira.

id (BIGINT, Primary Key, Auto-increment)

user_id (BIGINT, Foreign Key -> users.id, On Delete Cascade)

category_id (BIGINT, Foreign Key -> categories.id, On Delete Restrict)

account_name (VARCHAR(150)) — Nome descritivo (Ex: "Netflix", "Conta Gov").

login_username (VARCHAR(255)) — O e-mail ou nome de usuário daquela conta específica.

encrypted_password (TEXT) — A senha salva, criptografada usando a função Crypt::encryptString() do Laravel (AES-256-CBC).

timestamps (created_at e updated_at)

6. Migrations, Seeders e Carga Inicial do Banco
Para estruturar e povoar o banco de dados via DBeaver, os comandos foram disparados de dentro do ambiente isolado:

Bash
# Limpar o cache de rotas para o Laravel reconhecer o endpoint /api/hello
docker-compose exec app php artisan route:clear

# Executar a criação sequencial das tabelas obedecendo às restrições de chaves estrangeiras
docker-compose exec app php artisan migrate:fresh

# Alimentar a tabela de categorias com o catálogo padrão do ecossistema
docker-compose exec app php artisan db:seed --class=CategorySeeder