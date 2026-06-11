'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../lib/axios';

interface User {
    id: number;
    name: string;
    username: string;
    avatar: string | null;
}

export default function SearchPage() {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState<User[]>([]);
    const router = useRouter();

    function handleSearch(e: React.ChangeEvent<HTMLInputElement>) {
        const q = e.target.value;
        setQuery(q);
        if (q.trim() === '') { setResults([]); return; }
        api.get(`/users/search?q=${q}`).then(res => setResults(res.data));
    }

    return (
        <div className="max-w-xl mx-auto px-4 py-5 font-sans">
            <div className="flex items-center gap-2 mb-6 pb-4 border-b border-gray-300">
                <Link href="/feed" className="text-gray-800 text-xl no-underline">←</Link>
                <h2 className="text-base font-bold m-0">Search</h2>
            </div>

            <input
                type="text"
                placeholder="Search users..."
                value={query}
                onChange={handleSearch}
                className="w-full border border-gray-300 rounded px-3 py-2 mb-4 text-sm"
            />

            <div>
                {results.map(user => (
                    <div
                        key={user.id}
                        onClick={() => router.push(`/profile/${user.id}`)}
                        className="flex items-center gap-3 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50"
                    >
                        <div className="w-10 h-10 rounded-full bg-gray-200 overflow-hidden shrink-0">
                            {user.avatar
                                ? <img src={`http://127.0.0.1:8000/storage/${user.avatar}`} className="w-full h-full object-cover" alt="" />
                                : <div className="w-full h-full flex items-center justify-center font-bold">{user.name[0].toUpperCase()}</div>
                            }
                        </div>
                        <div>
                            <div className="font-bold text-sm">{user.username}</div>
                            <div className="text-xs text-gray-400">{user.name}</div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}