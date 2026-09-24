<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            /*
             * nullable dan nullOnDelete agar riwayat pesanan
             * tetap ada ketika produk dihapus admin.
             */
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            /*
             * Snapshot data produk saat dipesan.
             */
            $table->string('nama_produk');
            $table->string('gambar')->nullable();
            $table->decimal('harga', 15, 2);
            $table->unsignedInteger('jumlah');
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};