@php use Illuminate\Support\Facades\Storage; @endphp

<x-app-layout>
    <div class="max-w-lg mx-auto py-8 px-4">
        <form action="{{ route('users.search') }}" method="GET" class="mb-6">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search people..." autofocus
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </form>

        @if (isset($users))
            @forelse ($users as $user)
                <a href="{{ route('users.show', $user) }}"
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">
                        @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover"
                                alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-bold text-gray-600">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold">{{ $user->username ?? $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->name }}</p>
                    </div>
                </a>
            @empty
                <p class="text-center text-gray-500 text-sm">No users found.</p>
            @endforelse
        @endif
    </div>
</x-app-layout>
