<p align="center">
  <img src="public/images/teamflow_logo.svg" width="400" alt="TeamFlow Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/версия-1.0.0-blue" alt="Версия">
  <img src="https://img.shields.io/badge/Laravel-12.x-red" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-8892BF" alt="PHP">
  <img src="https://img.shields.io/badge/PostgreSQL-15-336791" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/лицензия-MIT-green" alt="Лицензия">
</p>

---

## О проекте

**TeamFlow** — веб-приложение для управления проектными задачами в команде, разработанный на Laravel 13. Проект предоставляет удобный интерфейс для организации задач, коммуникации внутри команды и отслеживания прогресса.


## Стек технологий

| Слой                | Технология                     |
|---------------------|--------------------------------|
| **Backend**         | Laravel 13, PHP 8.4            |
| **Frontend**        | Blade, Tailwind CSS, Alpine.js |
| **База данных**     | PostgreSQL 15, , Eloquent ORM  |
| **Веб-сервер**      | Nginx (Alpine)                 |
| **Контейнеризация** | Docker / Docker Compose        |
| **Отладка**         | Xdebug                         |

---

## Требования

Для работы с проектом необходимо установить:

- **Docker** и **Docker Compose**
- **Git**

---

## Установка и запуск

### 1. Клонирование репозитория

```
git clone <url-репозитория>
cd teamflow
```

### 2. Настройка окружения

Скопируйте файл переменных окружения и при необходимости отредактируйте его:

```
cp .env.example .env
```

### 3. Сборка и запуск контейнеров

```
docker compose up -d --build
```

### 4. Установка зависимостей PHP

```
docker compose exec laravel-app composer install
```

### 5. Генерация ключа приложения

```
docker compose exec laravel-app php artisan key:generate
```

### 6. Запуск миграций

```
docker compose exec laravel-app php artisan migrate
```

После этих шагов приложение доступно по адресу **http://localhost**.

---

## Структура Docker-окружения

Проект использует три сервиса, объединённых в сеть `laravel`:

```
laravel-app   — PHP-FPM контейнер с приложением Laravel
nginx         — веб-сервер, проксирует запросы к laravel-app
postgres      — база данных PostgreSQL 15
```

### Порты

| Сервис | Порт (хост → контейнер) |
|--------|------------------------|
| Nginx | `80 → 80` |
| PostgreSQL | `3377 → 5432` |

### Данные базы данных (по умолчанию)

| Параметр | Значение |
|----------|----------|
| База данных | `laravel-db` |
| Пользователь | `laravel-user` |
| Пароль | `pass` |
| Хост (внутри Docker) | `postgres` |
| Порт (снаружи) | `3377` |

---

## Полезные команды

### Управление контейнерами

```
# Запуск
docker compose up -d

# Остановка
docker compose down

# Просмотр логов
docker compose logs -f

# Статус контейнеров
docker compose ps
```

### Работа с Laravel

```
# Войти в контейнер приложения
docker compose exec laravel-app bash

# Запуск миграций
docker compose exec laravel-app php artisan migrate

# Откат миграций
docker compose exec laravel-app php artisan migrate:rollback

# Очистка кэша
docker compose exec laravel-app php artisan cache:clear
docker compose exec laravel-app php artisan config:clear

# Очередь задач
docker compose exec laravel-app php artisan queue:work
```


---

## Лицензия

TeamFlow распространяется под лицензией [MIT](https://opensource.org/licenses/MIT).
