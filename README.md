# Knowledge

Knowledge — веб-приложение для учебного процесса и проверки знаний. В системе есть:

- учебные предметы и материалы;
- тесты, вопросы и варианты ответов, попытки прохождения и результаты;
- аттестация и календарь событий;
- новости;
- учетные записи пользователей с ролями администратора и наставника.

## Технологии

- Laravel 12, PHP 8.2+;
- Vue 3, Inertia.js, TypeScript и Vite;
- SQLite по умолчанию;
- Docker: PHP-FPM, Nginx и Supervisor.

## Запуск через Docker Compose

Нужны Docker Engine и Docker Compose. Из корня проекта выполните:

```sh
docker compose up -d --build
```

При первом запуске контейнер подготовит SQLite-базу, применит миграции и сгенерирует ключ приложения. Откройте <http://localhost:8000>.

Чтобы выбрать другой порт, задайте `APP_PORT` в окружении перед запуском. Например, в PowerShell:

```powershell
$env:APP_PORT = "8080"
docker compose up -d --build
```

После этого приложение будет доступно на <http://localhost:8080>.

Контейнер сохраняет базу данных и файлы приложения в именованных Docker volumes. Полезные команды:

```sh
docker compose logs -f app
docker compose down
```

`docker compose down` останавливает и удаляет контейнер, но оставляет volumes с данными. Для локальной разработки установите `APP_DEBUG=true`; перед публичным развертыванием задайте `APP_ENV=production` и `APP_DEBUG=false`, настройте HTTPS и проверьте хранение ключа приложения и резервное копирование данных.

## Локальный запуск без Docker

Нужны PHP 8.2+, Composer, Node.js 22+ с npm и расширение PHP PDO SQLite.

1. Установите зависимости:

   ```sh
   composer install
   npm ci
   ```

2. Создайте `.env` из шаблона `.env.example` и настройте SQLite:

   ```sh
   cp .env.example .env
   ```

   В PowerShell вместо последней команды используйте `Copy-Item .env.example .env`.

3. Создайте пустой файл базы данных, если его еще нет:

   ```sh
   touch database/database.sqlite
   ```

   В PowerShell:

   ```powershell
   New-Item -ItemType File -Path database\database.sqlite -Force
   ```

4. Сгенерируйте ключ и примените миграции:

   ```sh
   php artisan key:generate
   php artisan migrate
   ```

5. Запустите приложение, очередь и Vite:

   ```sh
   composer run dev
   ```

Приложение будет доступно по адресу <http://localhost:8000>. Для сборки фронтенда без dev-сервера используйте `npm run build`.

## Проверки

Запуск тестов:

```sh
composer test
```
