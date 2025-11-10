The process of building small independent api as a service then connecting together as a single app

2. User Service

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

Response format:

```json
 {
  success: boolean
  data?: T
  error?: string
  message: string
  meta: PaginationMeta
}

interface PaginationMeta {
  total: number
  limit: number
  page: number
  total_pages: number
  has_next: boolean
  has_previous: boolean
}
```

### Task Execution

    You will be working as a group in teams of 4.
    Airtable link
    You’re required to write the CI/CD workflow for deployment.
    Request/Response/Model naming convention should be snake_case.
    To request for a server for deployment use the command /request-server

### User Service

    Manages user contact info (email, push tokens)
    Stores notification preferences
    Handles login and permissions
    Exposes REST APIs for user data

### Service Communication

Synchronous (REST): User preference lookups, Template retrieval, Status queries

### Data Storage Strategy

User Service: PostgreSQL (user data, preferences)

Shared Tools: Redis for caching user preferences, managing rate limits etc. and RabbitMQ/Kafka for async message queuing

### Failure Handling

Network Issues: Use local cache and continue essential operations gracefully.

### System Design Diagram

Each team must submit a simple diagram showing: Service connections, Queue structure, Retry and failure flow, Database relationships, Scaling plan.
You can use Draw.io, Miro, or Lucidchart (free tools).

### Performance Targets

    Handle 1,000+ notifications per minute
    API Gateway response under 100ms
    99.5% delivery success rate
    All services support horizontal scaling.

### Recommended Tech Stack

    Languages: PHP, Node.js (!express), Python, Go, or Java (Any language of your choice is welcomed)
    Queue: RabbitMQ or Kafka
    Database: PostgreSQL + Redis
    Containerization: Docker
    API Docs: OpenAPI or Swagger

### Learning Outcomes

This stage teaches:

    Microservices decomposition
    Asynchronous messaging patterns
    Distributed system failure handling
    Event-driven architecture design
    Scalable and fault-tolerant notification systems
    Team work and collaboration

### Submission Format

    Use the command /submit in the channel to make submission.
    Prepare to present your work (anyone can be called to answer any question).
    Deadline for submission Wednesday 12th of November, 2025. 11:59pm GMT +1 (WAT)

users
notification_preferences

contact info
notification preferences
login
permissions
rest api for user data

POST /api/v1/users/

```json

{
  name: str
  email: Email
  push_token: Optional[str]  # can be updated with an update endpoint
  preferences: UserPreference
  password: str
}

class UserPreference:
    email: bool
    push: bool

```

Endpoints

Success response:

```json
{
    "success": true,
    "message": "successful",
    "data": [],
    "meta": {
        "total": 100,
        "limit": 10,
        "page": 1,
        "total_pages": 10,
        "has_next": true,
        "has_previous": false
    }
}
```

Error response:

```json
{
    "success": false,
    "message": "something went wrong",
    "error": "validation fail"
}
```

### Using UUID in Laravel

```php

# migration
$table->uuid('id')->primary();

# relationship
$table->foreignUuid('user_id')->constrained();

$table->foreignUuid('user_id')->references('id')->on('users');


# model
protected $keyType = 'string';
public $incrementing = false;

# controller
$new_user->id = Str::uuid();

# from boot
public static function booted() {
    static::creating(function ($model) {
        $model->id = Str::uuid();
    });
}

# sanctum token
php artisan make:migration change_tokenable_id_type_in_personal_access_tokens_table
$table->foreignUuid('tokentable_id')->change();
$table->uuid('id')->primary()->change();

php artisan migrate

# 2019_12_14_000001_create_personal_access_tokens_table.php
$table->morphs('tokenable'); $table->id();
# to
$table->uuidMorphs('tokenable'); $table->uuid('id')->primary();

php artisan migrate:fresh


```

More: https://dev.to/adnanbabakan/implement-uuid-primary-key-in-laravel-and-its-benefits-55o3
Spatie permission: https://spatie.be/docs/laravel-permission/v6/advanced-usage/uuid
