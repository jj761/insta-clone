'use client';

import { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../../lib/axios';

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

export default function PostPage() {
    const params = useParams();
    const id = Array.isArray(params.id) ? params.id[0] : params.id;
    const [post, setPost] = useState<Post | null>(null);
    const [loading, setLoading] = useState(true);
    const router = useRouter();

    useEffect(() => {
        api.get(`/posts/${id}`)
            .then(res => {
                setPost(res.data);
                setLoading(false);
            })
            .catch(() => router.push('/feed'));
    }, [id]);

    if (loading) return <div className="text-center mt-24">Loading...</div>;
    if (!post) return null;

    return (
        <div className="max-w-xl mx-auto px-4 py-5 font-sans">
            <div className="flex items-center gap-2 mb-6 pb-4 border-b border-gray-300">
                <button onClick={() => router.back()} className="text-gray-800 text-xl bg-transparent border-none cursor-pointer">←</button>
                <h2 className="text-base font-bold m-0">Post</h2>
            </div>

            <div className="border border-gray-300 rounded bg-white">
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

                {post.image_path.endsWith('.mp4') || post.image_path.endsWith('.mov')
                    ? <video src={`http://127.0.0.1:8000/storage/${post.image_path}`} className="w-full block" controls />
                    : <img src={`http://127.0.0.1:8000/storage/${post.image_path}`} className="w-full block" alt="" />
                }

                {post.caption && (
                    <div className="px-4 py-2 text-sm">
                        <span className="font-bold">{post.user.username || post.user.name}</span> {post.caption}
                    </div>
                )}
                <div className="px-4 pb-3 text-xs text-gray-400">
                    {new Date(post.created_at).toLocaleDateString()}
                </div>
            </div>
        </div>
    );
}