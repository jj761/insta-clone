'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../lib/axios';

export default function RegisterPage() {
    const [form, setForm] = useState({ name: '', email: '', username: '', password: '' });
    const [error, setError] = useState('');
    const router = useRouter();

    function handleChange(e: React.ChangeEvent<HTMLInputElement>) {
        setForm({ ...form, [e.target.name]: e.target.value });
    }

    async function handleSubmit() {
        try {
            const res = await api.post('/register', form);
            localStorage.setItem('token', res.data.token);
            router.push('/feed');
        } catch {
            setError('Registration failed. Check your details.');
        }
    }

    return (
        <div className="max-w-sm mx-auto mt-24 px-4 font-sans">
            <h1 className="text-4xl text-center mb-6" style={{ fontFamily: 'cursive' }}>Instagram</h1>
            <div className="border border-gray-300 rounded p-6 mb-2">
                <input name="name" placeholder="Full Name" value={form.name} onChange={handleChange}
                    className="w-full border border-gray-300 rounded px-3 py-2 mb-2 text-sm" />
                <input name="username" placeholder="Username" value={form.username} onChange={handleChange}
                    className="w-full border border-gray-300 rounded px-3 py-2 mb-2 text-sm" />
                <input name="email" type="email" placeholder="Email" value={form.email} onChange={handleChange}
                    className="w-full border border-gray-300 rounded px-3 py-2 mb-2 text-sm" />
                <input name="password" type="password" placeholder="Password" value={form.password} onChange={handleChange}
                    className="w-full border border-gray-300 rounded px-3 py-2 mb-4 text-sm" />
                {error && <p className="text-red-500 text-sm mb-2">{error}</p>}
                <button onClick={handleSubmit}
                    className="w-full bg-blue-500 text-white font-bold py-2 rounded text-sm">
                    Sign Up
                </button>
            </div>
            <div className="border border-gray-300 rounded p-4 text-center text-sm">
                Have an account? <Link href="/login" className="text-blue-500 font-bold">Log in</Link>
            </div>
        </div>
    );
}