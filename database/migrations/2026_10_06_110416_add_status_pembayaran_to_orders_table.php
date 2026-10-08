<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        if (!Schema::hasColumn('orders', 'status_pembayaran')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status_pembayaran')
                    ->default('belum_dibayar');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_pembayaran');
        });
    }
};
