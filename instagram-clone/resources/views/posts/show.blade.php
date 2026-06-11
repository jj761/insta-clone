@php
    use Illuminate\Support\Facades\Storage;
    $isVideo = str_ends_with($post->image_path, '.mp4') || str_ends_with($post->image_path, '.mov');
@endphp

<x-app-layout>
    <div class="max-w-lg mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">
                    @if ($post->user->avatar)
                        <img src="{{ Storage::url($post->user->avatar) }}" class="w-full h-full object-cover"
                            alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center font-bold text-gray-600 text-sm">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <a href="{{ route('users.show', $post->user) }}" class="text-sm font-semibold hover:underline">
                    {{ $post->user->username ?? $post->user->name }}
                </a>
            </div>

            {{-- Media --}}
            @if ($isVideo)
                <video src="{{ Storage::url($post->image_path) }}" controls class="w-full"></video>
            @else
                <img src="{{ Storage::url($post->image_path) }}" alt="Post" class="w-full object-cover">
            @endif

            {{-- Caption --}}
            @if ($post->caption)
                <div class="px-4 py-3 text-sm">
                    <span class="font-semibold">{{ $post->user->username ?? $post->user->name }}</span>
                    {{ $post->caption }}
                </div>
            @endif

            {{-- Timestamp --}}
            <div class="px-4 pb-2 text-xs text-gray-400">
                {{ $post->created_at->diffForHumans() }}
            </div>

            {{-- Delete --}}
            @if ($post->user_id === auth()->id())
                <div class="px-4 pb-4">
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 text-sm hover:underline">
                            Delete Post
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
