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
    bio: string | null;
    posts_count: number;
    followers_count: number;
    following_count: number;
    followers: { id: number }[];
}

interface Post {
    id: number;
    image_path: string;
    caption: string;
}

export default function ProfilePage() {
    const params = useParams();
    const id = Array.isArray(params.id) ? params.id[0] : params.id;
    const [profile, setProfile] = useState<User | null>(null);
    const [posts, setPosts] = useState<Post[]>([]);
    const [me, setMe] = useState<{ id: number } | null>(null);
    const [isFollowing, setIsFollowing] = useState(false);
    const [loading, setLoading] = useState(true);
    const router = useRouter();

    useEffect(() => {
        Promise.all([
            api.get(`/users/${id}`),
            api.get('/user')
        ]).then(([profileRes, meRes]) => {
            setProfile({
                ...profileRes.data.user,
                posts_count: profileRes.data.posts_count,
                followers_count: profileRes.data.followers_count,
                following_count: profileRes.data.following_count,
                followers: profileRes.data.followers,
            });
            setPosts(profileRes.data.posts);
            setMe(meRes.data);
            setIsFollowing(profileRes.data.followers.some((f: { id: number }) => f.id === meRes.data.id));
            setLoading(false);
        }).catch(() => router.push('/feed'));
    }, [id]);

    function toggleFollow() {
        if (!profile) return;
        if (isFollowing) {
            api.delete(`/follow/${profile.id}`).then(() => {
                setIsFollowing(false);
                setProfile(p => p ? { ...p, followers_count: p.followers_count - 1 } : p);
            });
        } else {
            api.post(`/follow/${profile.id}`).then(() => {
                setIsFollowing(true);
                setProfile(p => p ? { ...p, followers_count: p.followers_count + 1 } : p);
            });
        }
    }

    if (loading) return <div className="text-center mt-24">Loading...</div>;
    if (!profile) return null;

    return (
        <div className="max-w-xl mx-auto px-4 py-5 font-sans">
            <div className="flex items-center gap-2 mb-6 pb-4 border-b border-gray-300">
                <Link href="/feed" className="text-gray-800 text-xl no-underline">←</Link>
                <h2 className="text-base font-bold m-0">{profile.username}</h2>
            </div>

            <div className="flex items-center gap-6 mb-6">
                <div className="w-20 h-20 rounded-full bg-gray-200 overflow-hidden shrink-0">
                    {profile.avatar
                        ? <img src={`http://127.0.0.1:8000/storage/${profile.avatar}`} className="w-full h-full object-cover" alt="" />
                        : <div className="w-full h-full flex items-center justify-center font-bold text-2xl">{profile.name[0].toUpperCase()}</div>
                    }
                </div>
                <div>
                    <div className="flex gap-6 mb-2 text-sm">
                        <span><strong>{profile.posts_count}</strong> posts</span>
                        <span><strong>{profile.followers_count}</strong> followers</span>
                        <span><strong>{profile.following_count}</strong> following</span>
                    </div>
                    <div className="font-bold text-sm">{profile.name}</div>
                    {profile.bio && <div className="text-sm">{profile.bio}</div>}
                    {me && me.id !== profile.id && (
                        <button onClick={toggleFollow}
                            className={`mt-2 px-4 py-1 rounded text-sm font-bold border ${isFollowing ? 'bg-white text-gray-800 border-gray-300' : 'bg-blue-500 text-white border-blue-500'}`}>
                            {isFollowing ? 'Unfollow' : 'Follow'}
                        </button>
                    )}
                </div>
            </div>

            <div className="grid grid-cols-3 gap-1">
                {posts.map(post => (
                    <Link key={post.id} href={`/posts/${post.id}`} className="aspect-square bg-gray-100 overflow-hidden block">
                        {post.image_path.endsWith('.mp4') || post.image_path.endsWith('.mov')
                            ? <video src={`http://127.0.0.1:8000/storage/${post.image_path}`} className="w-full h-full object-cover" />
                            : <img src={`http://127.0.0.1:8000/storage/${post.image_path}`} className="w-full h-full object-cover" alt="" />
                        }
                    </Link>
                ))}
            </div>
        </div>
    );
}