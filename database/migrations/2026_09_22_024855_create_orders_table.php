<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nomor_pesanan')->unique();

            /*
             * Data pelanggan disimpan lagi sebagai snapshot.
             * Jadi alamat pesanan lama tidak ikut berubah apabila
             * pelanggan mengubah profil.
             */
            $table->string('nama_pelanggan');
            $table->string('email');
            $table->string('no_hp', 25);
            $table->text('alamat');

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('ongkos_kirim', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->string('metode_pembayaran')->nullable();

            $table->enum('status', [
                'menunggu',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan',
            ])->default('menunggu');

            $table->timestamp('tanggal_pesanan')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};