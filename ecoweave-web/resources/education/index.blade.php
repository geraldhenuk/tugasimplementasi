<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Portal Edukasi & Tips Perawatan 📚</h1>

            <form method="GET" action="{{ route('education.index') }}" class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel atau tips..." class="border-gray-300 rounded-md shadow-sm">
                <select name="category" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Topik</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Cari Artikel</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($articles as $article)
                    <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col sm:flex-row">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full sm:w-44 h-44 object-cover">
                        <div class="p-4 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-semibold text-emerald-600">{{ $article->category->name }}</span>
                                <h3 class="text-lg font-bold text-gray-800 mt-1">{{ $article->title }}</h3>
                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $article->content }}</p>
                            </div>
                            <a href="{{ route('education.show', $article->id) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold mt-3 inline-block">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>