<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <!-- Banner Status AI -->
            <div class="bg-gradient-to-r from-green-700 to-emerald-600 rounded-xl p-6 text-white shadow-md flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}! 🌿</h1>
                    <p class="text-green-100 mt-1">Temukan tanaman hijau dan tips perawatan yang dikurasi khusus untuk Anda.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    @if($aiStatus === 'active')
                        <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-semibold">✨ AI Recommendation: Aktif</span>
                    @else
                        <span class="bg-yellow-500/30 px-3 py-1 rounded-full text-xs font-semibold">⚡ Mode Katalog Standar (Fallback)</span>
                    @endif
                </div>
            </div>

            <!-- Bagian Rekomendasi Tanaman -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Rekomendasi Tanaman Untukmu</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col justify-between">
                            <div>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">{{ $product->category->name }}</span>
                                    <h3 class="text-lg font-bold text-gray-800 mt-2">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $product->description }}</p>
                                </div>
                            </div>
                            <div class="p-4 border-t flex justify-between items-center bg-gray-50">
                                <span class="font-bold text-green-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">Stok: {{ $product->stock }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bagian Rekomendasi Artikel Edukasi -->
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tips & Edukasi Pilihan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($articles as $article)
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col sm:flex-row">
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full sm:w-40 h-40 object-cover">
                            <div class="p-4 flex flex-col justify-between">
                                <div>
                                    <span class="text-xs font-semibold text-emerald-600">{{ $article->category->name }}</span>
                                    <h3 class="text-md font-bold text-gray-800 mt-1">{{ $article->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $article->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>