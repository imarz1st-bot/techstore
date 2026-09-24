<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_pesanan',
        'nama_pelanggan',
        'email',
        'no_hp',
        'alamat',
        'subtotal',
        'ongkos_kirim',
        'total',
        'metode_pembayaran',
        'status',
        'tanggal_pesanan',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'ongkos_kirim' => 'decimal:2',
        'total' => 'decimal:2',
        'tanggal_pesanan' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}