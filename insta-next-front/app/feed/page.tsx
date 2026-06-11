'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../lib/axios';

interface User {
    id: number;
    name: string;
    username: string;
    avatar: string | null;
}

interface Post {
    id: number;
    image_path: string;
    caption: string;
    created_at: string;
    user: User;
}

interface Story {
    id: number;
    image_path: string;
    user: User;
}

export default function FeedPage() {
    const [posts, setPosts] = useState<Post[]>([]);
    const [stories, setStories] = useState<Story[]>([]);
    const [me, setMe] = useState<User | null>(null);
    const [loading, setLoading] = useState(true);
    const router = useRouter();

    useEffect(() => {
        Promise.all([
            api.get('/feed'),
            api.get('/user'),
            api.get('/stories')
        ]).then(([feedRes, meRes, storiesRes]) => {
            setPosts(feedRes.data);
            setMe(meRes.data);
            setStories(storiesRes.data);
            setLoading(false);
        }).catch((err) => {
            console.error('Profile error:', err);
            router.push('/feed');
        });
    }, []);

    function logout() {
        api.post('/logout').finally(() => {
            localStorage.removeItem('token');
            router.push('/login');
        });
    }

    if (loading) return <div className="text-center mt-24">Loading...</div>;

    return (
        <div className="max-w-xl mx-auto px-4 py-5 font-sans">
            {/* Navbar */}
            <div className="flex justify-between items-center mb-6 pb-4 border-b border-gray-300">
                <h1 className="text-2xl" style={{ fontFamily: 'cursive' }}>Instagram</h1>
                <div className="flex gap-4 items-center text-sm font-bold">
                    <Link href="/posts/create" className="text-sm font-bold text-gray-800 no-underline">New Post</Link>
                    <Link href="/search" className="text-gray-800 no-underline">Search</Link>
                    <Link href="/stories/create" className="text-gray-800 no-underline">New Story</Link>
                    {me && <Link href={`/profile/${me.id}`} className="text-gray-800 no-underline">My Profile</Link>}
                    <button onClick={logout} className="text-gray-800 font-bold bg-transparent border-none cursor-pointer">
                        Log out
                    </button>
                </div>
            </div>

            {/* Stories row */}
            {stories.length > 0 && (
                <div className="flex gap-4 overflow-x-auto pb-4 mb-4 border-b border-gray-300">
                    {stories.map(story => (
                        <Link key={story.id} href={`/stories/${story.id}`} className="flex flex-col items-center gap-1 shrink-0 no-underline">
                            <div className="w-14 h-14 rounded-full overflow-hidden border-2 border-pink-500 p-0.5 box-border">
                                <img
                                    src={`http://127.0.0.1:8000/storage/${story.image_path}`}
                                    className="w-full h-full object-cover rounded-full"
                                    alt=""
                                />
                            </div>
                            <span className="text-xs text-gray-800">{story.user?.username || story.user?.name}</span>
                        </Link>
                    ))}
                </div>
            )}

            {/* Posts */}
            {posts.length === 0 ? (
                <p className="text-center text-gray-400 mt-16">Your feed is empty. Follow people to see posts.</p>
            ) : (
                posts.map(post => (
                    <div key={post.id} className="border border-gray-300 rounded mb-6 bg-white">
                        <div className="flex items-center gap-2 px-4 py-3">
                            <div className="w-8 h-8 rounded-full bg-gray-200 overflow-hidden">
                                {post.user.avatar
                                    ? <img src={`http://127.0.0.1:8000/storage/${post.user.avatar}`} className="w-full h-full object-cover" alt="" />
                                    : <div className="w-full h-full flex items-center justify-center font-bold text-sm">{post.user.name[0].toUpperCase()}</div>
                                }
                            </div>
                            <Link href={`/profile/${post.user.id}`} className="font-bold text-sm text-gray-800 no-underline">
                                {post.user.username || post.user.name}
                            </Link>
                        </div>

                        {post.image_path.endsWith('.mp4') || post.image_path.endsWith('.mov') ? (
                            <video src={`http://127.0.0.1:8000/storage/${post.image_path}`} className="w-full block" controls />
                        ) : (
                            <img src={`http://127.0.0.1:8000/storage/${post.image_path}`} className="w-full block" alt="Post" />
                        )}

                        {post.caption && (
                            <div className="px-4 py-2 text-sm">
                                <span className="font-bold">{post.user.username || post.user.name}</span> {post.caption}
                            </div>
                        )}
                        <div className="px-4 pb-3 text-xs text-gray-400">
                            {new Date(post.created_at).toLocaleDateString()}
                        </div>
                    </div>
                ))
            )}
        </div>
    );
}