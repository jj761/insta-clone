# Instagram Clone — Frontend

Next.js 16 client-side frontend for the Instagram clone. Communicates with the Laravel backend exclusively via REST API using Sanctum Bearer tokens.

---

## Tech Stack

| | |
|---|---|
| Framework | Next.js 16.2 (App Router) |
| Language | TypeScript |
| HTTP | Axios with Bearer token interceptor |
| CSS | Tailwind CSS v4 |
| React | 19.2 |

---

## Setup

### Requirements
- Node.js 18+
- Laravel backend running at `http://127.0.0.1:8000`

### Installation

```bash
cd insta-next-front
npm install
npm run dev
```

Runs on `http://localhost:3000`.

---

## Project Structure

```
app/
├── lib/
│   └── axios.ts              # Axios instance, token interceptor
├── layout.tsx                # Root layout
├── page.tsx                  # Root redirect
├── globals.css
│
├── login/
│   └── page.tsx              # Login form
├── register/
│   └── page.tsx              # Registration form
├── feed/
│   └── page.tsx              # Feed: posts + stories row + navbar
├── search/
│   └── page.tsx              # Live user search
├── posts/
│   ├── create/page.tsx       # Create post (image or video)
│   └── [id]/page.tsx         # View single post
├── stories/
│   ├── create/page.tsx       # Create story (image only)
│   └── [id]/page.tsx         # View single story (9:16 layout)
└── profile/
    └── [id]/page.tsx         # User profile, follow/unfollow, post grid
```

---

## Authentication

Token-based via Laravel Sanctum.

- On login or register, the API returns a plaintext token.
- Token is stored in `localStorage` under the key `token`.
- `app/lib/axios.ts` attaches it as `Authorization: Bearer <token>` on every request via an Axios request interceptor.
- On logout, the token is deleted from `localStorage` and the `/api/logout` endpoint is called to revoke it server-side.

**Note:** `localStorage` is accessible to JavaScript on the page, which makes it vulnerable to XSS. This is acceptable for a learning project but not suitable for production.

---

## Pages

### `/login`
Email + password form. On success, stores token and redirects to `/feed`.

### `/register`
Name, username, email, password form. On success, stores token and redirects to `/feed`. The backend dispatches a welcome email asynchronously.

### `/feed`
Main page. Fetches three endpoints in parallel on mount:
- `GET /api/feed` — posts from followed users
- `GET /api/user` — current user (for profile link)
- `GET /api/stories` — stories from self + followed users

Displays a horizontal stories row at the top, then a post list. Supports image and video posts. Includes navbar with links to New Post, Search, New Story, My Profile, and Log Out.

### `/search`
Live search — calls `GET /api/users/search?q=` on every keystroke. Clicking a result navigates to that user's profile.

### `/posts/create`
File picker (image or video, `jpg/jpeg/png/mp4/mov`). Preview before submit. Submits as `multipart/form-data` with optional caption. Redirects to `/feed` on success.

### `/posts/[id]`
Fetches `GET /api/posts/{id}`. Renders image or video, caption, username, and date.

### `/stories/create`
Image-only file picker. Submits as `multipart/form-data`. Redirects to `/feed` on success.

### `/stories/[id]`
Fetches `GET /api/stories/{id}`. Renders in a 9:16 aspect ratio container (portrait). Supports image and video.

### `/profile/[id]`
Fetches `GET /api/users/{id}` and `GET /api/user` in parallel. Displays:
- Avatar, bio, post/follower/following counts
- Follow/Unfollow button (hidden when viewing own profile)
- Post grid (3 columns), links to individual posts

Follow state is derived by checking if the current user's ID appears in the profile's `followers` array. On follow/unfollow, follower count is updated optimistically in local state.

---

## API Communication

All requests go through `app/lib/axios.ts`:

```typescript
const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});
```

Media files (avatars, post images, story images) are served directly from the Laravel storage:

```
http://127.0.0.1:8000/storage/{path}
```

This URL is hardcoded in multiple page components. Changing the backend URL requires updating it in every page.

---

