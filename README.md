# Instagram Clone

A full-stack Instagram clone built as a learning project. The backend is a Laravel 13 REST API with a separate Blade/Livewire layer for admin tooling. The frontend is a Next.js 16 app communicating with the API over Sanctum token auth.

---

## Architecture specifications 

```
insta-clone/
├── instagram-clone/        # Laravel 13 backend
│   ├── REST API            # Consumed by Next.js frontend (Sanctum tokens)
│   ├── Blade frontend      # Session-auth UI (Laravel Breeze)
│   └── Livewire admin      # Admin dashboard, server-side reactive UI
│
└── insta-next-front/       # Next.js 16 frontend
    └── Talks to /api/*     # Token stored in localStorage
```

Two rendering approaches coexist intentionally:
- **Next.js** — client-side React app, talks to Laravel via REST API, token auth
- **Blade + Livewire** — server-rendered, session auth, used for the admin dashboard

Both share the same database and models.

---

## Tech Stack

### Backend (`instagram-clone/`)
| | |
|---|---|
| Framework | Laravel 13 |
| PHP | 8.5 |
| Database | MySQL 8.0 |
| Auth (API) | Laravel Sanctum (Bearer tokens) |
| Auth (Web) | Laravel Breeze (session) |
| Admin UI | Livewire v3 |
| CSS | Tailwind CSS v4 + Vite |
| Queue | Laravel database queue driver |
| Mail | Queued welcome email on registration |

### Frontend (`insta-next-front/`)
| | |
|---|---|
| Framework | Next.js 16.2 (App Router) |
| Language | TypeScript |
| HTTP | Axios (with Bearer token interceptor) |
| CSS | Tailwind CSS v4 |

---

## Features

### Core
- Register and login (token-based via API, session-based via Blade)
- Queued welcome email sent on registration
- Follow and unfollow users
- User search (live, by username)
- User profiles with post grid, follower/following counts

### Posts
- Create posts with image or video upload
- Feed showing posts from followed users only, newest first
- View individual posts

### Stories
- Create image stories
- Stories feed shows your own + people you follow
- View individual stories

### Admin Dashboard (Blade + Livewire)
- Stats: total users, posts, follows, stories
- Accessible at `/admin`, visible in navbar to admin users only
- Protected at middleware level — non-admins get 403
- Built as a Livewire full-page component with a separate admin layout

---

## Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| username | string | unique |
| password | string | bcrypt hashed |
| bio | text | nullable |
| avatar | string | nullable, path in storage |
| is_admin | boolean | default false |
| email_verified_at | timestamp | nullable |

### `posts`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| user_id | bigint | FK → users |
| image_path | string | path in storage, supports video |
| caption | text | nullable |

### `stories`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| user_id | bigint | FK → users |
| image_path | string | path in storage |
| created_at | timestamp | no updated_at (timestamps = false) |

### `follows`
| Column | Type | Notes |
|---|---|---|
| follower_id | bigint | FK → users |
| following_id | bigint | FK → users |

### `personal_access_tokens`
Standard Sanctum table for API token storage.

---

## API Reference

Base URL: `http://127.0.0.1:8000/api`

### Public (no auth required)
| Method | Endpoint | Description |
|---|---|---|
| POST | `/register` | Register. Returns user + token. Queues welcome email. |
| POST | `/login` | Login. Returns user + token. |

### Protected (Bearer token required)
| Method | Endpoint | Description |
|---|---|---|
| GET | `/user` | Authenticated user object |
| POST | `/logout` | Revokes current token |
| GET | `/feed` | Posts from followed users, newest first |
| POST | `/posts` | Create post (multipart, `image` field) |
| GET | `/posts/{id}` | Single post |
| DELETE | `/posts/{id}` | Delete post |
| POST | `/follow/{userId}` | Follow a user |
| DELETE | `/follow/{userId}` | Unfollow a user |
| GET | `/users/search?q=` | Search users by username |
| GET | `/users/{id}` | User profile with posts, follower counts |
| GET | `/stories` | Stories from self + followed users |
| POST | `/stories` | Create story (image only, jpg/jpeg/png) |
| GET | `/stories/{id}` | Single story |

---

## Web Routes (Blade, session auth)

| Route | Description |
|---|---|
| `/dashboard` | Feed (Blade) |
| `/posts/create` | Create post form |
| `/posts/{id}` | View post |
| `/users/{user}` | User profile |
| `/search?q=` | User search |
| `/stories/{id}` | View story |
| `/profile` | Edit own profile |
| `/admin` | Livewire admin dashboard (admin only) |

---

## Getting Started

### Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+
- MySQL 8.0

### Backend Setup

```bash
cd instagram-clone

composer install

cp .env.example .env
# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD
# Set QUEUE_CONNECTION=database
# Configure MAIL_* for welcome emails

php artisan key:generate
php artisan migrate
php artisan storage:link
```

Start the backend:

```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Queue worker (required for welcome emails)
php artisan queue:work

# Terminal 3 — Vite (for Blade/admin frontend)
npm install
npm run dev
```

### Frontend Setup

```bash
cd insta-next-front
npm install
npm run dev
```

Runs on `http://localhost:3000`. Expects backend at `http://127.0.0.1:8000`.

---

## Admin Access

To grant admin access via tinker:

```bash
php artisan tinker
User::find(1)->update(['is_admin' => true]);
```

Admin users see an **Admin** link in the navbar. Non-admins hitting `/admin` directly get a 403.

---

## Project Structure

### Backend (`instagram-clone/`)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # REST API controllers (Sanctum)
│   │   │   ├── AuthController.php
│   │   │   ├── FeedController.php
│   │   │   ├── PostController.php
│   │   │   ├── StoryController.php
│   │   │   ├── FollowController.php
│   │   │   └── UserController.php
│   │   └── ...               # Blade controllers (session auth)
│   └── Middleware/
│       └── EnsureUserIsAdmin.php
├── Jobs/
│   └── SendWelcomeEmail.php  # Queued on registration
├── Livewire/
│   └── AdminDashboard.php    # Full-page Livewire component
└── Models/
    ├── User.php
    ├── Post.php
    └── Story.php

resources/views/
├── layouts/
│   ├── admin.blade.php       # Admin layout (Livewire)
│   └── navigation.blade.php
└── livewire/
    └── admin-dashboard.blade.php
```

### Frontend (`insta-next-front/`)

```
app/
├── lib/
│   └── axios.ts              # Axios instance with Bearer token interceptor
├── feed/page.tsx             # Feed — posts + stories
├── login/page.tsx
├── register/page.tsx
├── search/page.tsx           # Live user search
├── posts/
│   ├── create/page.tsx
│   └── [id]/page.tsx
├── stories/
│   ├── create/page.tsx
│   └── [id]/page.tsx
└── profile/
    └── [id]/page.tsx         # Profile with follow/unfollow, post grid
```

---
