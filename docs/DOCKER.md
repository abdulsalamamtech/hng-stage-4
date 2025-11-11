# Docker Setup Guide

This project includes Docker configuration for both local development and production environments.

## Local Development Setup

### Files Used:

-   `Dockerfile` - PHP-FPM development image with SQLite
-   `Dockerfile.nginx` - Nginx reverse proxy
-   `docker-compose.yml` - Local development compose file
-   `nginx/default.conf` - Nginx configuration

### Architecture:

```
Nginx (port 80) → PHP-FPM app (port 9000) → SQLite database
                ↓
             Redis (port 6379)
```

### Getting Started:

1. **Build and start containers:**

```bash
docker-compose up -d --build
```

2. **Install dependencies (if first run):**

```bash
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build
```

3. **Create database and run migrations:**

```bash
docker-compose exec app php artisan migrate
```

4. **Access the application:**

-   Open `http://localhost` in your browser

### Useful Commands:

```bash
# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan <command>

# Run tests
docker-compose exec app php artisan test

# Access the app shell
docker-compose exec app bash

# Stop containers
docker-compose down
```

## Production Setup

### Files Used:

-   `Dockerfile.prod` - Optimized PHP-FPM production image
-   `Dockerfile.nginx` - Nginx reverse proxy (same as local)
-   `docker-compose.prod.yml` - Production compose file with MySQL & Redis
-   `nginx/default.conf` - Nginx configuration

### Architecture:

```
Nginx (port 80) → PHP-FPM app (port 9000) → MySQL database
                ↓
             Redis (port 6379)
```

### Deployment:

1. **Build images for production:**

```bash
docker-compose -f docker-compose.prod.yml build
```

2. **Start services:**

```bash
docker-compose -f docker-compose.prod.yml up -d
```

3. **Run migrations:**

```bash
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

4. **Clear caches:**

```bash
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## Environment Variables

### Local Development (.env)

-   `APP_ENV=local`
-   `APP_DEBUG=true`
-   `DB_CONNECTION=sqlite`
-   `REDIS_HOST=redis`

### Production

-   `APP_ENV=production`
-   `APP_DEBUG=false`
-   `DB_CONNECTION=mysql`
-   `DB_HOST=db`
-   `DB_USERNAME=laravel`
-   `DB_PASSWORD=secret` (change in production!)

## Database

### Local Development

-   Uses SQLite (file-based database at `database/database.sqlite`)
-   No additional database service required
-   Automatically synced with code volume

### Production

-   Uses MySQL 8.0
-   Data persisted in Docker volume `dbdata`
-   Database name: `laravel`
-   Default credentials in `docker-compose.prod.yml` (update for security!)

## Notes

-   Both development and production use the same Nginx configuration
-   PHP version: 8.2 (Alpine)
-   Redis 7 (Alpine) for caching and queues
-   All containers use the `laravel` network for inter-service communication
-   The development environment uses code volumes for live reloading
-   Production image is optimized with multi-stage build for smaller image size
