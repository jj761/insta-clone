@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">

        {{-- Profile Header --}}
        <div class="flex items-center gap-10 mb-10">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                @else
                    <div class="w-full h-full flex items-center justify-center text-3xl text-gray-500">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-4 mb-2">
                    <h1 class="text-xl font-semibold">{{ $user->username ?? $user->name }}</h1>
                    @if ($user->id !== auth()->id())
                        @if (auth()->user()->following->contains('id', $user->id))
                            <form action="{{ route('follow.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="px-4 py-1 text-sm border border-gray-400 rounded font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Unfollow
                                </button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user->id) }}" method="POST">
                                @csrf
                                <button
                                    class="px-4 py-1 text-sm bg-blue-500 text-white rounded font-medium hover:bg-blue-600">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
                <div class="flex gap-6 text-sm mb-3">
                    <span><strong>{{ $posts->count() }}</strong> posts</span>
                    <span><strong>{{ $user->followers()->count() }}</strong> followers</span>
                    <span><strong>{{ $user->following()->count() }}</strong> following</span>
                </div>
                @if ($user->bio)
                    <p class="text-sm">{{ $user->bio }}</p>
                @endif
            </div>
        </div>

        {{-- Divider --}}
        <hr class="border-gray-300 dark:border-gray-700 mb-6">

        {{-- Posts Grid --}}
        @if ($posts->isEmpty())
            <p class="text-center text-gray-500 text-sm">No posts yet.</p>
        @else
            <div class="grid grid-cols-3 gap-1">
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="aspect-square overflow-hidden block">
                        <img src="{{ Storage::url($post->image_path) }}"
                            class="w-full h-full object-cover hover:opacity-80 transition-opacity" alt="Post">
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
