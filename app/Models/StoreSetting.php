<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'nama_toko',
        'email',
        'no_hp',
        'alamat',
        'logo',
        'shipping_type',
        'shipping_cost',
    ];

    protected $casts = [
        'shipping_cost' => 'integer',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'nama_toko' => 'TechStore',
                'email' => 'info@techstore.com',
            ]
        );
    }

    public function shippingFee(): int
    {
        return $this->shipping_type === 'flat'
            ? $this->shipping_cost
            : 0;
    }
}