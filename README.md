# attendance-management
## 環境構築
### Dockerビルド
1. `git clone git@github.com:Mika-F620/attendance-management.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
`php artisan key:generate`
6. マイグレーションの実行
7. シーディングの実行
`php artisan db:seed`

### MailHog環境構築
1. `docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog`
2. .envに以下の環境変数を追加
```
MAIL_MAILER=smtp
MAIL_HOST=host.docker.internal
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 使用技術(実行環境)
PHP8.3.13  
Laravel8.83.28  
MySQL10.3.39  

## ER図
![er2](https://github.com/user-attachments/assets/b396a367-059f-4f23-b4b3-69286beaecec)

## URL
開発環境：http://localhost/  
phpMyAdmin:http://localhost:8080/  
MailHog:http://localhost:8025/
