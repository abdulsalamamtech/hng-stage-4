<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

Stage 4 Backend Task: Microservices & Message QueuesTask
Title: Distributed Notification System

This project focus on building a notification system that sends emails and push notifications using separate microservices. Each service communicate asynchronously through a message queue (e.g., RabbitMQ or Kafka).

Task Execution: We worked as a group in teams of 4.

This project contain a CI/CD workflow for deployment.

#### User Service

    Responsible for user contact info and preferences.
    DB: PostgreSQL.

    Responsibilities:

    store user emails, push_tokens, notification_preferences (opt_in/out).

    auth/login/permissions.

    REST API for lookups:

    GET /v1/users/{user_id}

    GET /v1/users/{user_id}/preferences
    expose /health

    Shared cache: Redis for caching preferences & rate-limits.

![](./docs/user-service-database-design.png)

### Deployment

```sh

git clone https://github.com/abdulsalamamtech/hng-stage-4
cd hng-stage-4

cp .env.example .env

composer install
npm install

php artisan migrate
composer dev

```

## Documentation endpoint

This project used the open API documentation library for Laravel.

production-url: is the production url where the application is running.

Access the OpenAPI Documentation: (http://production-url/docs/api)

![](./docs/api-docs-image.png)

### variable

@test = http://localhost:8000
@live = https://api.example.com
@url = {{test}}

### Get all users

GET {{url}}/api/v1/users?limit=20&page=1

### Get user by ID

GET {{url}}/api/v1/users/10

### Create new user

POST {{url}}/api/v1/users
Content-Type: application/json

{
"name": "My User",
"email": "user1111@example.com",
"password": "securepassword",
"push_token": "queue_token"
}

### Update user

PUT {{url}}/api/v1/users/16
Content-Type: application/json

{
"name": "Updated Name",
"preference": {
"email": true,
"push": false
}
}

### Delete user

DELETE {{url}}/api/v1/users/12

### Register new user

POST {{url}}/api/auth/register
Content-Type: application/json

{
"name": "My User",
"email": "mainuser@example.com",
"password": "securepassword",
"push_token": "queue_token"
}

### Login user

POST {{url}}/api/auth/login  
Content-Type: application/json

{
"email": "mainuser@example.com",
"password": "securepassword"
}

### Test route

GET {{url}}/api/test

### Test route

GET {{url}}/api/health

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
