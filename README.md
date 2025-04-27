# News API

API for news and comments with websocket support.

## Requirements

-   PHP 8.1 or higher
-   Composer
-   MySQL 8.0 or higher
-   Node.js 18 or higher (for websockets)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/sashakukharuk/api-news.git
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
BROADCAST_DRIVER=reverb
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

## Running the System

### Start Web Server

```bash
php artisan serve
```

### Start Websockets

```bash
php artisan reverb:start
```

### Start Frontend Development Server

```bash
npm run dev
```

### Testing the Application

1. Open your browser and navigate to: http://localhost:8000/

2. Create a test comment through the API with news_id=1

After creating the comment, you should see it appear on the page in real-time.

## Testing

### Run Tests

```bash
php artisan test
```

## API Documentation

API documentation is available at:

```
http://localhost:8000/swagger
```
