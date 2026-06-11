'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../lib/axios';

export default function LoginPage() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    async function handleSubmit() {
        try {
            const res = await api.post('/login', { email, password });
            localStorage.setItem('token', res.data.token);
            router.push('/feed');
        } catch {
            setError('Invalid credentials.');
        }
    }

    return (
        <div className="max-w-sm mx-auto mt-24 px-4 font-sans">
            <h1 className="text-4xl font-cursive text-center mb-6" style={{ fontFamily: 'cursive' }}>Instagram</h1>
            <div className="border border-gray-300 rounded p-6 mb-2">
                <input
                    type="email"
                    placeholder="Email"
                    value={email}
                    onChange={e => setEmail(e.target.value)}
                    className="w-full border border-gray-300 rounded px-3 py-2 mb-2 text-sm"
                />
                <input
                    type="password"
                    placeholder="Password"
                    value={password}
                    onChange={e => setPassword(e.target.value)}
                    className="w-full border border-gray-300 rounded px-3 py-2 mb-4 text-sm"
                />
                {error && <p className="text-red-500 text-sm mb-2">{error}</p>}
                <button
                    onClick={handleSubmit}
                    className="w-full bg-blue-500 text-white font-bold py-2 rounded text-sm"
                >
                    Log In
                </button>
            </div>
            <div className="border border-gray-300 rounded p-4 text-center text-sm">
                No account? <Link href="/register" className="text-blue-500 font-bold">Sign up</Link>
            </div>
        </div>
    );
}