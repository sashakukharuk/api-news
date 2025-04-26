# News API

API for news and comments with websocket support.

## Requirements

-   PHP 8.1 or higher
-   Composer
-   MySQL 8.0 or higher
-   Node.js 16 or higher (for websockets)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/api-news.git
cd api-news
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install Node.js dependencies:

```bash
npm install
```

4. Copy configuration file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure database in `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_news
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:

```bash
php artisan migrate
```

8. Seed the database with test data (optional):

```bash
php artisan db:seed
```

## Environment Setup

### Sentry Configuration

Configure Sentry in `.env` file:

```env
SENTRY_LARAVEL_DSN=your-sentry-dsn
SENTRY_TRACES_SAMPLE_RATE=1.0
```

### Websocket Configuration

1. Configure websockets in `.env` file:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

## Running the System

### Start Web Server

```bash
php artisan serve
```

### Start Websockets

```bash
php artisan websockets:serve
```

## Testing

### Run Tests

```bash
php artisan test
```

### Run Tests with Coverage

```bash
php artisan test --coverage
```

## API Documentation

API documentation is available at:

```
http://localhost:8000/swagger
```
