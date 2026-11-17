<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin & Customer Contoh
        $adminId = DB::table('users')->insertGetId([
            'name'       => 'Admin EcoWeave',
            'email'      => 'admin@ecoweave.com',
            'password'   => Hash::make('password123'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $customerId = DB::table('users')->insertGetId([
            'name'       => 'Budi Santoso',
            'email'      => 'budi@gmail.com',
            'password'   => Hash::make('password123'),
            'role'       => 'customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Buat Kategori (Produk & Artikel)
        $catIndoor = DB::table('categories')->insertGetId([
            'name'       => 'Tanaman Indoor',
            'type'       => 'product',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catSukulen = DB::table('categories')->insertGetId([
            'name'       => 'Kaktus & Sukulen',
            'type'       => 'product',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catOutdoor = DB::table('categories')->insertGetId([
            'name'       => 'Tanaman Outdoor',
            'type'       => 'product',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catTipsPemula = DB::table('categories')->insertGetId([
            'name'       => 'Tips Perawatan Pemula',
            'type'       => 'article',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catEkologi = DB::table('categories')->insertGetId([
            'name'       => 'Edukasi Lingkungan',
            'type'       => 'article',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Simpan Preferensi Onboarding Awal untuk Budi (Minimal 3 kategori)
        DB::table('user_preferences')->insert([
            ['user_id' => $customerId, 'category_id' => $catIndoor],
            ['user_id' => $customerId, 'category_id' => $catSukulen],
            ['user_id' => $customerId, 'category_id' => $catTipsPemula],
        ]);

        // 4. Buat Produk Tanaman Contoh
        DB::table('products')->insert([
            [
                'category_id' => $catIndoor,
                'name'        => 'Monstera Deliciosa',
                'description' => 'Tanaman hias daun berlubang yang estetik. Cocok untuk sudut ruangan dengan cahaya tidak langsung. Menyiram cukup 2 kali seminggu.',
                'price'       => 125000,
                'stock'       => 15,
                'image_url'   => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=500',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => $catIndoor,
                'name'        => 'Lidah Mertua (Sansevieria)',
                'description' => 'Sangat bandel untuk pemula dan efektif menyerap polutan udara di dalam ruangan.',
                'price'       => 45000,
                'stock'       => 30,
                'image_url'   => 'https://images.unsplash.com/photo-1593482892290-f54927ae1bb6?w=500',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => $catSukulen,
                'name'        => 'Echeveria Elegans',
                'description' => 'Sukulen berbentuk mawar mini yang menggemaskan. Butuh media tanam porous dan sinar matahari pagi.',
                'price'       => 30000,
                'stock'       => 25,
                'image_url'   => 'https://images.unsplash.com/photo-1509423350716-97f9360b4e09?w=500',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => $catOutdoor,
                'name'        => 'Bougainvillea Merah',
                'description' => 'Tanaman bunga kertas yang tahan panas terik dan berbunga lebat sepanjang tahun.',
                'price'       => 85000,
                'stock'       => 10,
                'image_url'   => 'https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=500',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // 5. Buat Artikel Edukasi Contoh
        DB::table('articles')->insert([
            [
                'category_id' => $catTipsPemula,
                'title'       => '5 Kesalahan Penyiraman yang Sering Membunuh Tanaman Indoor',
                'content'     => "Banyak pemula mengira tanaman layu selalu karena kurang air, padahal overwatering (kelebihan air) jauh lebih sering menyebabkan busuk akar.\n\n1. Menyiram tanpa mengecek kelembapan tanah.\n2. Pot tidak memiliki lubang drainase di bagian bawah.\n3. Menggunakan air keran yang terlalu dingin.\n\nSelalu masukkan jari Anda sedalam 2 cm ke dalam tanah sebelum menyiram!",
                'image_url'   => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=500',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => $catEkologi,
                'title'       => 'Bagaimana Tanaman Hias Mengurangi Jejak Karbon di Rumah Anda',
                'content'     => "Selain mempercantik estetika ruangan, tanaman hijau melakukan fotosintesis yang menyerap karbon dioksida (CO2) dan melepaskan oksigen segar.\n\nBeberapa spesies seperti Sansevieria bahkan tetap memproduksi oksigen di malam hari dan menyaring senyawa organik volatil (VOC) dari cat dinding maupun furnitur.",
                'image_url'   => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?w=500',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}