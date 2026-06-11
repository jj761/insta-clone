'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../../lib/axios';

export default function CreateStoryPage() {
    const [image, setImage] = useState<File | null>(null);
    const [preview, setPreview] = useState<string | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const router = useRouter();

    function handleImage(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0] || null;
        setImage(file);
        if (file) {
            setPreview(URL.createObjectURL(file));
        }
    }

    function handleSubmit() {
        if (!image) {
            setError('Please select an image.');
            return;
        }
        setLoading(true);
        setError('');
        const formData = new FormData();
        formData.append('image', image);
        api.post('/stories', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        }).then(() => {
            router.push('/feed');
        }).catch(() => {
            setError('Failed to create story. Please try again.');
            setLoading(false);
        });
    }

    return (
        <div className="max-w-xl mx-auto px-4 py-5 font-sans">
            <div className="flex items-center gap-2 mb-6 pb-4 border-b border-gray-300">
                <Link href="/feed" className="text-gray-800 text-xl no-underline">←</Link>
                <h2 className="text-base font-bold m-0">New Story</h2>
            </div>

            <div className="flex flex-col gap-4">
                {preview && (
                    <div className="w-full aspect-square bg-gray-100 overflow-hidden rounded">
                        {image?.type.startsWith('video/')
                            ? <video src={preview} className="w-full h-full object-cover" controls />
                            : <img src={preview} className="w-full h-full object-cover" alt="" />
                        }
                    </div>
                )}

            <input
            type="file"
            accept="image/*,video/*"
            onChange={handleImage}
            className="hidden"
            id="fileInput"
            />
            <label htmlFor="fileInput" className="w-full border-2 border-dashed border-gray-300 rounded py-8 flex flex-col items-center justify-center cursor-pointer hover:border-gray-400">
            <span className="text-2xl mb-2">📷</span>
            <span className="text-sm text-gray-500">{image ? image.name : 'Click to select photo or video'}</span>
            </label> 

                {error && <p className="text-red-500 text-sm">{error}</p>}

                <button
                    onClick={handleSubmit}
                    disabled={loading}
                    className="w-full bg-blue-500 text-white py-2 rounded font-bold text-sm disabled:opacity-50">
                    {loading ? 'Sharing...' : 'Share'}
                </button>
            </div>
        </div>
    );
}