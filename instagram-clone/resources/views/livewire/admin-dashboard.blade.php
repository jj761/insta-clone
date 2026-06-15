<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-900">← Back to Feed</a>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">Total Users</p>
            <p class="text-4xl font-bold text-gray-900">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">Total Posts</p>
            <p class="text-4xl font-bold text-gray-900">{{ $totalPosts }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">Total Follows</p>
            <p class="text-4xl font-bold text-gray-900">{{ $totalFollows }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">Total Stories</p>
            <p class="text-4xl font-bold text-gray-900">{{ $totalStories }}</p>
        </div>
    </div>
</div>
