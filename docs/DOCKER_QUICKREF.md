# Docker Quick Reference

## 🚀 Quick Start - Local Development

```bash
# Build and start all services
docker-compose up -d --build

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build

# Setup database
docker-compose exec app php artisan migrate

# Access: http://localhost
```

## 🚀 Quick Start - Production

```bash
# Build production images
docker-compose -f docker-compose.prod.yml build

# Start services
docker-compose -f docker-compose.prod.yml up -d

# Setup database
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

---

## 📋 Common Commands

### Container Management

```bash
# View running containers
docker-compose ps

# Stop all containers
docker-compose down

# Remove volumes (careful with data!)
docker-compose down -v

# View logs
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f redis
```

### Laravel Commands

```bash
# Run Artisan commands
docker-compose exec app php artisan <command>

# Examples:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Application Access

```bash
# Interactive shell in app container
docker-compose exec app bash

# Run PHP directly
docker-compose exec app php -v

# Run Composer
docker-compose exec app composer <command>

# Run NPM
docker-compose exec app npm <command>

# Run Tests
docker-compose exec app php artisan test
```

### Database

```bash
# Access SQLite (local dev)
docker-compose exec app sqlite3 database/database.sqlite

# Access MySQL (production)
docker-compose -f docker-compose.prod.yml exec db mysql -u laravel -p
# Password: secret (change in production!)
```

### Debugging

```bash
# Check service status
docker-compose ps

# View full logs
docker-compose logs

# Check specific service
docker-compose logs app

# Real-time logs with follow
docker-compose logs -f app

# View last N lines
docker-compose logs --tail=50 app
```

---

## 🏗️ Architecture

### Local Development

-   **Nginx**: Reverse proxy (port 80)
-   **PHP-FPM**: Application server (port 9000)
-   **SQLite**: File-based database
-   **Redis**: Cache/queue store (port 6379)

### Production

-   **Nginx**: Reverse proxy (port 80/443)
-   **PHP-FPM**: Application server (port 9000)
-   **MySQL**: Database server (port 3306)
-   **Redis**: Cache/queue store (port 6379)

---

## 📝 Environment Differences

### Local (.env with Docker)

```
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
REDIS_HOST=redis
REDIS_PORT=6379
```

### Production (.env)

```
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=db
DB_USERNAME=laravel
DB_PASSWORD=<secure_password>
REDIS_HOST=redis
REDIS_PORT=6379
```

---

## 🔧 Troubleshooting

### Container won't start

```bash
# Check logs
docker-compose logs app

# Rebuild image
docker-compose build --no-cache app

# Clean up and restart
docker-compose down -v
docker-compose up -d --build
```

### Database migration errors

```bash
# Check database connection
docker-compose exec app php artisan tinker
# In tinker: DB::connection()->getPdo()

# Fresh migration
docker-compose exec app php artisan migrate:fresh

# With seeding
docker-compose exec app php artisan migrate:fresh --seed
```

### Permission errors

```bash
# Fix file permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache

# Fix ownership
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Port already in use

```bash
# Change port in docker-compose.yml
# Example: "8080:80" instead of "80:80"

# Or stop conflicting service
sudo lsof -i :80  # Find process on port 80
kill -9 <PID>     # Kill process
```

---

## 📂 File Structure

```
.
├── Dockerfile              # Local development image
├── Dockerfile.nginx        # Nginx image
├── Dockerfile.prod         # Production image
├── docker-compose.yml      # Local development
├── docker-compose.prod.yml # Production
├── nginx/
│   └── default.conf        # Nginx configuration
├── .dockerignore          # Files to exclude from image
├── app/                    # Laravel app code
├── database/
│   ├── database.sqlite     # Local database (created on first run)
│   ├── migrations/
│   └── seeders/
├── resources/              # Frontend code
└── docs/
    ├── DOCKER.md          # Full guide
    ├── DOCKER_FIXES.md    # What was fixed
    └── DOCKER_VALIDATION.md # Validation checklist
```

---

## ✅ All Configurations Fixed

-   [x] Development Dockerfile (SQLite)
-   [x] Nginx service in docker-compose
-   [x] Nginx Dockerfile
-   [x] Production compose file (MySQL)
-   [x] Path and volume configuration
-   [x] Environment variables
-   [x] Service dependencies
-   [x] Documentation

**Ready to use! 🚀**

### Local Development

```sh
docker-compose up -d --build
docker-compose exec app composer install
docker-compose exec app npm install && npm run build
docker-compose exec app php artisan migrate
# Access: http://localhost

```

### Production

```sh
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

```

✨ Architecture

Local: Nginx (80) → PHP-FPM (9000) → SQLite + Redis
Production: Nginx (80/443) → PHP-FPM (9000) → MySQL + Redis
