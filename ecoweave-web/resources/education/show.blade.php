<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 px-4">
            <article class="bg-white rounded-xl shadow overflow-hidden">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-64 sm:h-80 object-cover">
                <div class="p-6 sm:p-8">
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-100 px-2.5 py-1 rounded">{{ $article->category->name }}</span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-3">{{ $article->title }}</h1>
                    <p class="text-xs text-gray-400 mt-1">Dipublikasikan pada {{ $article->created_at->format('d M Y') }}</p>

                    <div class="mt-6 text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $article->content }}
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <form action="{{ route('interactions.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $article->id }}">
                            <input type="hidden" name="item_type" value="article">
                            <input type="hidden" name="interaction_type" value="like">
                            <button type="submit" class="border {{ $isLiked ? 'border-pink-500 text-pink-600 bg-pink-50' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }} font-semibold py-2 px-6 rounded-lg transition">
                                {{ $isLiked ? '❤️ Artikel Disukai' : '🤍 Suka Artikel Ini' }}
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>