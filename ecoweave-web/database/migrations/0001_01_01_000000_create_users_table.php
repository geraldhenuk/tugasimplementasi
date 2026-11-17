<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'customer'])->default('customer');
            $table->timestamps();
        });

        // 2. Tabel Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['product', 'article']);
            $table->timestamps();
        });

        // 3. Tabel User Preferences (Data Onboarding AI)
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });

        // 4. Tabel Products (Katalog Tanaman)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->integer('stock');
            $table->string('image_url');
            $table->timestamps();
        });

        // 5. Tabel Articles (Konten Edukasi)
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->longText('content');
            $table->string('image_url');
            $table->timestamps();
        });

        // 6. Tabel Carts (Keranjang Belanja)
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        // 7. Tabel Transactions (Data Checkout Dummy)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->text('dummy_address'); // Akan dienkripsi via Model Casts
            $table->string('invoice_path')->nullable();
            $table->timestamps();
        });

        // 8. Tabel Transaction Details
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price_at_purchase', 12, 2);
        });

        // 9. Tabel Interactions (Dataset AI Python)
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->enum('item_type', ['product', 'article']);
            $table->enum('interaction_type', ['click', 'like', 'rating', 'purchase']);
            $table->tinyInteger('rating_value')->nullable();
            $table->timestamps();

            // Indexing untuk mempercepat query pembacaan dataset oleh Python
            $table->index(['user_id', 'item_type', 'interaction_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactions');
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('products');
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};