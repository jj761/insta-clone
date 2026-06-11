# Insta Clone

A full stack Instagram clone built with a Laravel REST API backend and a Next.js frontend.

## Tech Stack

**Backend**
- PHP / Laravel
- MySQL
- Laravel Sanctum (token authentication)

**Frontend**
- Next.js 13 (App Router)
- TypeScript
- Tailwind CSS
- Axios

## Features

- Register and login with token-based authentication
- View a feed of posts from people you follow
- View and create posts (images and videos)
- View and create stories
- View user profiles with post grid
- Follow and unfollow users
- Search for users

## Project Structure

```
insta-clone/
├── instagram-clone/       # Laravel backend
└── insta-next-front/      # Next.js frontend
```

## Getting Started

### Backend

```bash
cd instagram-clone
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

Make sure your `.env` has the correct database credentials before migrating.

### Frontend

```bash
cd insta-next-front
npm install
npm run dev
```

The frontend runs on `http://localhost:3000` and expects the backend at `http://127.0.0.1:8000`.

## API

The backend exposes a REST API under `/api`. All routes except `/api/register` and `/api/login` require a Bearer token via Laravel Sanctum.
