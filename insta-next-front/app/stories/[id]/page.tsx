'use client';

import { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import api from '../../lib/axios';

interface User {
    id: number;
    name: string;
    username: string;
    avatar: string | null;
}

interface Story {
    id: number;
    image_path: string;
    user: User;
}

export default function StoryPage() {
    const params = useParams();
    const id = Array.isArray(params.id) ? params.id[0] : params.id;
    const [story, setStory] = useState<Story | null>(null);
    const [loading, setLoading] = useState(true);
    const router = useRouter();

    useEffect(() => {
        api.get(`/stories/${id}`)
            .then(res => {
                setStory(res.data);
                setLoading(false);
            })
            .catch(() => router.push('/feed'));
    }, [id]);

    if (loading) return <div className="text-center mt-24">Loading...</div>;
    if (!story) return null;

    return (
        <div className="max-w-xl mx-auto px-4 py-5 font-sans">
            <div className="flex items-center gap-2 mb-4">
                <button onClick={() => router.push('/feed')} className="text-gray-800 text-xl bg-transparent border-none cursor-pointer">←</button>
                <div className="w-8 h-8 rounded-full bg-gray-200 overflow-hidden">
                    {story.user.avatar
                        ? <img src={`http://127.0.0.1:8000/storage/${story.user.avatar}`} className="w-full h-full object-cover" alt="" />
                        : <div className="w-full h-full flex items-center justify-center font-bold text-sm">{story.user.name[0].toUpperCase()}</div>
                    }
                </div>
                <span className="font-bold text-sm">{story.user.username || story.user.name}</span>
            </div>

            <div className="w-full aspect-[9/16] bg-black rounded overflow-hidden">
                {story.image_path.endsWith('.mp4') || story.image_path.endsWith('.mov')
                    ? <video src={`http://127.0.0.1:8000/storage/${story.image_path}`} className="w-full h-full object-cover" controls autoPlay />
                    : <img src={`http://127.0.0.1:8000/storage/${story.image_path}`} className="w-full h-full object-cover" alt="" />
                }
            </div>
        </div>
    );
}