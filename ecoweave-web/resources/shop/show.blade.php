<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="bg-white rounded-xl shadow overflow-hidden grid grid-cols-1 md:grid-cols-2">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-80 md:h-full object-cover">
                
                <div class="p-6 md:p-8 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold text-green-600 bg-green-100 px-2.5 py-1 rounded">{{ $product->category->name }}</span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-3">{{ $product->name }}</h1>
                        <p class="text-2xl font-bold text-green-700 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">Tersedia: <strong>{{ $product->stock }}</strong> unit</p>
                        
                        <div class="mt-4 border-t pt-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Deskripsi & Perawatan:</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $product->description }}</p>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3">
                        <!-- Form Tambah ke Keranjang -->
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 border-gray-300 rounded-lg text-center">
                            <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition">
                                + Tambah ke Keranjang
                            </button>
                        </form>

                        <!-- Tombol Like (Explicit Feedback untuk AI) -->
                        <form action="{{ route('interactions.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $product->id }}">
                            <input type="hidden" name="item_type" value="product">
                            <input type="hidden" name="interaction_type" value="like">
                            <button type="submit" class="w-full border {{ $isLiked ? 'border-pink-500 text-pink-600 bg-pink-50' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }} font-semibold py-2 px-4 rounded-lg transition">
                                {{ $isLiked ? '❤️ Disukai (Tersimpan untuk AI)' : '🤍 Suka Tanaman Ini' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>