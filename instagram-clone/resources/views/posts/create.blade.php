<x-app-layout>
    <div class="max-w-lg mx-auto py-10 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8">
            <h1 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Create Post</h1>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Image</label>
                    <input type="file" name="image" id="image" accept="image/*,video/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-500 file:text-white hover:file:bg-blue-600 cursor-pointer">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Caption</label>
                    <textarea name="caption" id="caption" rows="4"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Write a caption..."></textarea>
                    @error('caption')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Share Post
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
