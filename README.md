# Unc Inc Backend Assessment

## Overview

**Extra Challenges Implemented**
- **Performance:** Caching for `GET /api/articles` and `GET /api/articles/{id}` with Redis, plus DB indexing.
- **Asynchronous Processing:** Laravel Queue (Redis) pushes article analysis jobs instead of calling the Python service directly.

**Design Trade-offs**
- **Time vs. Depth:** Focused on delivering a working, integrated system within the time constraint rather than extensive test coverage or advanced architecture patterns.
- **Convenience vs. Isolation:** Shared one Redis instance for both caching and queues to minimize configuration, at the cost of strict separation of responsibilities.

## Setup

### Environment setup
1. Run `make setup`
2. cd api && php artisan key:generate
3. Run `make up`

### Prerequisites
- Docker & Docker Compose
- Make (for convenience)

### Commands

```bash
# 1. Copy .env files
make setup

# 2. Start all services
make up

# 3. Stop and clean up
make down
```

## Laravel API

### Authentication

#### `POST /api/auth/login`
Authenticate user and return a session token.

**Body**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

### Articles

#### `GET /api/articles`
Paginated list of articles (public).
Supports caching per page.

#### `GET /api/articles/{id}`
Fetch single article (public).
Cached by article ID.

#### `POST /api/articles`
Create a new article (authenticated).
Triggers background job to call the Python microservice.

#### `PUT /api/articles/{id}`
Update an existing article (author or admin only).
Also re-analyzes via background job.

#### `DELETE /api/articles/{id}`
Delete article (author or admin only).

## Python NLP Microservice

### Endpoint
`POST /analyze`

**Input**
```json
{
  "content": "Full article text..."
}
```

**Output**
```json
{
  "summary": "Short summary of the content.",
  "keywords": ["keyword1", "keyword2", "keyword3"]
}
```


## Testing Endpoints with Bruno

All endpoints are defined in the included **Bruno files**:

To test:
1. Open Bruno.
2. Import the bruno folder.
3. Use the `local` environment to hit your local API.
4. Authenticate via `/api/auth/login` before using POST, PUT, or DELETE endpoints.

