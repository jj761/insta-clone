@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <div class="max-w-sm mx-auto mt-8">
        <div class="relative rounded-xl overflow-hidden bg-black" style="aspect-ratio: 9/16;">
            <img src="{{ Storage::url($story->image_path) }}" class="w-full h-full object-cover" alt="">
            <div
                class="absolute top-0 left-0 right-0 p-4 flex items-center gap-3 bg-gradient-to-b from-black/60 to-transparent">
                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white flex-shrink-0">
                    @if ($story->user->avatar)
                        <img src="{{ Storage::url($story->user->avatar) }}" class="w-full h-full object-cover"
                            alt="">
                    @else
                        <div
                            class="w-full h-full bg-gray-400 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($story->user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <span class="text-white text-sm font-semibold">{{ $story->user->username ?? $story->user->name }}</span>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to feed</a>
        </div>
    </div>
</x-app-layout>
