@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <div class="max-w-lg mx-auto py-8 px-4 space-y-6">

        {{-- Stories Row --}}
        <div class="flex gap-4 overflow-x-auto pb-2">
            {{-- Upload Your Story --}}
            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col items-center gap-1 flex-shrink-0">
                @csrf
                <label
                    class="w-16 h-16 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer overflow-hidden">
                    <span class="text-2xl text-gray-400">+</span>
                    <input type="file" name="image" accept="image/*" class="hidden"
                        onchange="this.closest('form').submit()">
                </label>
                <span class="text-xs text-gray-500">Your story</span>
            </form>

            {{-- Others' Stories --}}
            @foreach ($stories as $story)
                <a href="{{ route('stories.show', $story) }}" class="flex flex-col items-center gap-1 flex-shrink-0">
                    <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-pink-500">
                        <img src="{{ Storage::url($story->image_path) }}" class="w-full h-full object-cover"
                            alt="">
                    </div>
                    <span class="text-xs text-gray-600">{{ $story->user->username ?? $story->user->name }}</span>
                </a>
            @endforeach
        </div>

        <hr class="border-gray-200">

        {{-- Posts Feed --}}
        @if ($posts->isEmpty())
            <div class="text-center text-gray-400 py-20">
                <p class="text-lg font-medium text-gray-700">Your feed is empty.</p>
                <p class="text-sm mt-1">Follow people to see their posts here.</p>
            </div>
        @else
            @foreach ($posts as $post)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    {{-- Post Header --}}
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                            @if ($post->user->avatar)
                                <img src="{{ Storage::url($post->user->avatar) }}" class="w-full h-full object-cover"
                                    alt="">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-sm font-bold text-gray-500">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('users.show', $post->user) }}"
                            class="text-sm font-semibold text-gray-900 hover:underline">
                            {{ $post->user->username ?? $post->user->name }}
                        </a>
                    </div>

                    {{-- Post Media --}}
                    @php $isVideo = str_ends_with($post->image_path, '.mp4') || str_ends_with($post->image_path, '.mov'); @endphp
                    @if ($isVideo)
                        <a href="{{ route('posts.show', $post) }}">
                            <video src="{{ Storage::url($post->image_path) }}" class="w-full"></video>
                        </a>
                    @else
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ Storage::url($post->image_path) }}" class="w-full object-cover"
                                alt="Post">
                        </a>
                    @endif

                    {{-- Caption --}}
                    @if ($post->caption)
                        <div class="px-4 py-3 text-sm text-gray-900">
                            <span class="font-semibold">{{ $post->user->username ?? $post->user->name }}</span>
                            {{ $post->caption }}
                        </div>
                    @endif

                    {{-- Timestamp --}}
                    <div class="px-4 pb-3 text-xs text-gray-400">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
