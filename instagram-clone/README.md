# Instagram Clone — Backend

Laravel 13 backend serving two separate interfaces: a REST API consumed by the Next.js frontend, and a Blade/Livewire web interface with its own session-based auth.

---

## Tech Stack

| | |
|---|---|
| Framework | Laravel 13 |
| PHP | 8.5 |
| Database | MySQL 8.0 |
| API Auth | Laravel Sanctum (Bearer tokens) |
| Web Auth | Laravel Breeze (session) |
| Admin UI | Livewire v4 |
| CSS | Tailwind CSS v4 + Vite |
| Queue | Database driver |
| Mail | Queued on registration |

---

## Setup

### Requirements
- PHP 8.4+
- Composer
- MySQL 8.0
- Node.js 18+ (for Vite/Tailwind compilation)

### Installation

```bash
cd instagram-clone

composer install
npm install

cp .env.example .env
php artisan key:generate
```

### Environment

Edit `.env` and set:

```env
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@example.com
```

### Database

```bash
php artisan migrate
php artisan storage:link
```

### Running

```bash
# Terminal 1 — API + web server
php artisan serve

# Terminal 2 — Queue worker (required for welcome emails)
php artisan queue:work

# Terminal 3 — Vite (required for Blade/admin UI)
npm run dev
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                    # REST API (Sanctum token auth)
│   │   │   ├── AuthController.php
│   │   │   ├── FeedController.php
│   │   │   ├── PostController.php
│   │   │   ├── StoryController.php
│   │   │   ├── FollowController.php
│   │   │   └── UserController.php
│   │   ├── Auth/                   # Breeze session auth controllers
│   │   ├── FeedController.php      # Blade feed
│   │   ├── PostController.php      # Blade post management
│   │   ├── StoryController.php     # Blade stories
│   │   ├── FollowController.php    # Blade follow/unfollow
│   │   └── UserController.php      # Blade user profiles + search
│   └── Middleware/
│       └── EnsureUserIsAdmin.php   # 403 for non-admins on /admin
├── Jobs/
│   └── SendWelcomeEmail.php        # Dispatched on registration
├── Livewire/
│   └── AdminDashboard.php          # Full-page component, stats display
├── Mail/
│   └── WelcomeMail.php
└── Models/
    ├── User.php
    ├── Post.php
    └── Story.php

database/migrations/
├── create_users_table.php
├── add_profile_fields_to_users_table.php   # username, bio, avatar
├── create_posts_table.php
├── create_follows_table.php
├── create_stories_table.php
├── create_personal_access_tokens_table.php # Sanctum
└── add_is_admin_to_users_table.php

resources/views/
├── layouts/
│   ├── admin.blade.php             # Livewire admin layout
│   └── navigation.blade.php        # Nav with admin link gated by is_admin
└── livewire/
    └── admin-dashboard.blade.php
```

---

## Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| username | string | unique |
| password | string | bcrypt |
| bio | text | nullable |
| avatar | string | nullable, relative storage path |
| is_admin | boolean | default false |
| email_verified_at | timestamp | nullable |

### `posts`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| user_id | bigint | FK → users |
| image_path | string | relative storage path, supports video |
| caption | text | nullable |

### `stories`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| user_id | bigint | FK → users |
| image_path | string | images only (jpg/jpeg/png) |
| created_at | timestamp | `updated_at` disabled |

### `follows`
| Column | Type | Notes |
|---|---|---|
| follower_id | bigint | FK → users |
| following_id | bigint | FK → users |

---

## REST API Reference

Base URL: `http://127.0.0.1:8000/api`

All protected routes require: `Authorization: Bearer <token>`

### Auth

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/register` | None | Creates user, dispatches welcome email, returns token |
| POST | `/login` | None | Returns token |
| POST | `/logout` | Required | Revokes current token |
| GET | `/user` | Required | Returns authenticated user object |

**Register request body:**
```json
{
  "name": "string, required",
  "email": "string, required, unique",
  "username": "string, required, unique",
  "password": "string, min:8"
}
```

**Login request body:**
```json
{
  "email": "string, required",
  "password": "string, required"
}
```

**Auth response:**
```json
{
  "user": { "id": 1, "name": "...", "username": "...", ... },
  "token": "plaintext-sanctum-token"
}
```

---

### Feed

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| GET | `/feed` | Required | Posts from followed users, newest first, with user eager-loaded |

---

### Posts

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/posts` | Required | Create post. Multipart form. |
| GET | `/posts/{id}` | Required | Single post with user |
| DELETE | `/posts/{id}` | Required | Delete own post. Returns 403 if not owner. |

**POST `/posts` — form fields:**
| Field | Type | Required |
|---|---|---|
| image | file | Yes — jpg, jpeg, png, mp4, mov |
| caption | string | No |

---

### Stories

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| GET | `/stories` | Required | Stories from self + followed users, newest first |
| POST | `/stories` | Required | Create story. Multipart form. |
| GET | `/stories/{id}` | Required | Single story with user |

**POST `/stories` — form fields:**
| Field | Type | Required |
|---|---|---|
| image | file | Yes — jpg, jpeg, png only |

---

### Users

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| GET | `/users/{id}` | Required | Profile: user object + posts + follower/following counts + follower list |
| GET | `/users/search?q=` | Required | Search by username or name (LIKE) |

**GET `/users/{id}` response shape:**
```json
{
  "user": { ... },
  "posts_count": 12,
  "followers_count": 5,
  "following_count": 3,
  "posts": [ ... ],
  "followers": [ { "id": 2 }, ... ]
}
```

---

### Follow

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/follow/{userId}` | Required | Follow a user. Returns 422 if following self. |
| DELETE | `/follow/{userId}` | Required | Unfollow a user. |

---

## Web Routes (Blade)

Session auth via Laravel Breeze.

| Route | Controller | Notes |
|---|---|---|
| GET `/dashboard` | FeedController | Feed page |
| GET `/posts/create` | PostController | Create post form |
| POST `/posts` | PostController | Store post |
| GET `/posts/{id}` | PostController | View post |
| DELETE `/posts/{id}` | PostController | Delete post |
| GET `/users/{user}` | UserController | Profile page |
| GET `/search` | UserController | User search |
| POST `/follow/{userId}` | FollowController | Follow |
| DELETE `/follow/{userId}` | FollowController | Unfollow |
| GET `/stories/{id}` | StoryController | View story |
| POST `/stories` | StoryController | Create story |
| GET `/admin` | AdminDashboard (Livewire) | Admin only, 403 otherwise |

---

## Admin Dashboard

Livewire full-page component at `/admin`.

- Displays: total users (excluding admins), total posts, total follows, total stories
- Access: visible in navbar only to users with `is_admin = true`
- Enforced: `EnsureUserIsAdmin` middleware returns 403 for non-admins

**Grant admin access:**
```bash
php artisan tinker
User::find(1)->update(['is_admin' => true]);
```

---
