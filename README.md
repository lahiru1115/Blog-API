# Blog API

A RESTful API for a blogging platform built with **Laravel 11** and **Laravel Sanctum** for token-based authentication. Supports user registration/login, post management with draft/published statuses, and a comment system.

---

## Tech Stack

- **Framework:** Laravel 11
- **Authentication:** Laravel Sanctum
- **Database:** SQLite (default) / MySQL
- **Testing:** PHPUnit 11

---

## Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/lahiru1115/Blog-API.git
   cd Blog-API
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

> Import the Postman collection and SQL dump from the `docs/` folder for quick setup and testing.

---

## API Endpoints

Base URL: `http://localhost:8000/api`

### Authentication

| Method | Endpoint    | Description             | Auth Required |
|--------|-------------|-------------------------|---------------|
| POST   | `/register` | Register a new user     | No            |
| POST   | `/login`    | Login and get API token | No            |
| GET    | `/user`     | Get authenticated user  | Yes           |
| POST   | `/logout`   | Logout (revoke token)   | Yes           |

### Posts

| Method | Endpoint              | Description                          | Auth Required |
|--------|-----------------------|--------------------------------------|---------------|
| GET    | `/posts`              | Get all published posts (searchable) | No            |
| GET    | `/my-posts`           | Get authenticated user's posts       | Yes           |
| POST   | `/new-post`           | Create a new post                    | Yes           |
| PUT    | `/update-post/{id}`   | Update a post                        | Yes           |
| DELETE | `/delete-post/{id}`   | Delete a post                        | Yes           |

### Comments

| Method | Endpoint                 | Description      | Auth Required |
|--------|--------------------------|------------------|---------------|
| POST   | `/new-comment`           | Add a comment    | Yes           |
| PUT    | `/update-comment/{id}`   | Update a comment | Yes           |
| DELETE | `/delete-comment/{id}`   | Delete a comment | Yes           |

---

## Authentication

Protected routes require a Bearer token in the `Authorization` header:

```
Authorization: Bearer <your-token>
```

The token is returned on successful login or registration.

---

## Request Payloads

### Register — `POST /register`
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Password1!",
  "password_confirmation": "Password1!"
}
```

> Password must be at least 8 characters with mixed case, numbers, and symbols.

### Login — `POST /login`
```json
{
  "email": "john@example.com",
  "password": "Password1!"
}
```

### Create / Update Post — `POST /new-post` · `PUT /update-post/{id}`
```json
{
  "title": "My First Post",
  "body": "Post content goes here.",
  "status": "published"
}
```

> `status` accepts `published` or `draft`.

### Add / Update Comment — `POST /new-comment` · `PUT /update-comment/{id}`
```json
{
  "post_id": 1,
  "body": "Great post!"
}
```

---

## Database Schema

### users
| Column       | Type      | Notes        |
|--------------|-----------|--------------|
| id           | bigint    | Primary key  |
| name         | string    |              |
| email        | string    | Unique       |
| password     | string    | Hashed       |
| created_at   | timestamp |              |
| updated_at   | timestamp |              |
| deleted_at   | timestamp | Soft delete  |

### posts
| Column       | Type      | Notes                   |
|--------------|-----------|-------------------------|
| id           | bigint    | Primary key             |
| user_id      | bigint    | FK → users              |
| title        | string    |                         |
| body         | text      |                         |
| status       | enum      | `published` / `draft`   |
| created_at   | timestamp |                         |
| updated_at   | timestamp |                         |
| deleted_at   | timestamp | Soft delete             |

### comments
| Column       | Type      | Notes       |
|--------------|-----------|-------------|
| id           | bigint    | Primary key |
| post_id      | bigint    | FK → posts  |
| user_id      | bigint    | FK → users  |
| body         | text      |             |
| created_at   | timestamp |             |
| updated_at   | timestamp |             |
| deleted_at   | timestamp | Soft delete |

---

## Running Tests

```bash
php artisan test
```

---

## Docs

The `docs/` folder contains:

- `Blog-API.postman_collection.json` — Import into Postman to test all endpoints
- `blog-api.sql` — SQL dump for database setup
