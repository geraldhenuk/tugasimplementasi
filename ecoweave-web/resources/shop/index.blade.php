<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 px-4 sm:px-0">Katalog Tanaman Hijau 🌿</h1>

            <!-- Form Search & Filter -->
            <form method="GET" action="{{ route('shop.index') }}" class="bg-white p-4 rounded-lg shadow mb-6 mx-4 sm:mx-0 grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tanaman..." class="border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                
                <select name="category" class="border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="sort" class="border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Urutkan: Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                </select>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition">
                    Filter Tanaman
                </button>
            </form>

            <!-- Grid Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @forelse ($products as $product)
                    <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col justify-between">
                        <div>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">{{ $product->category->name }}</span>
                                <h3 class="text-lg font-bold text-gray-800 mt-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $product->description }}</p>
                            </div>
                        </div>
                        <div class="p-4 border-t bg-gray-50 flex justify-between items-center">
                            <div>
                                <span class="block font-bold text-green-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">Stok: {{ $product->stock }}</span>
                            </div>
                            <a href="{{ route('shop.show', $product->id) }}" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3 text-center py-8">Tanaman tidak ditemukan.</p>
                @endforelse
            </div>

            <div class="mt-6 px-4 sm:px-0">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>