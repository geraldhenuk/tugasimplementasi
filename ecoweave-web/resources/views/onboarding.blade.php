<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h2 class="text-2xl font-bold text-green-800 mb-2">Selamat Datang di EcoWeave! 🌱</h2>
                <p class="text-gray-600 mb-6">Pilih minimal <strong>3 kategori</strong> tanaman atau topik edukasi yang paling Anda minati agar AI kami dapat menyiapkan rekomendasi personal untuk Anda.</p>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('onboarding.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach ($categories as $category)
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-green-50 transition">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                <div class="ml-3">
                                    <span class="block font-semibold text-gray-800">{{ $category->name }}</span>
                                    <span class="text-xs text-gray-500 uppercase">{{ $category->type === 'product' ? 'Kategori Tanaman' : 'Topik Edukasi' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                        Simpan Minat & Masuk ke Beranda
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>