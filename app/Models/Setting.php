<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_name', 'logo', 'phone', 'email', 'address',
        'tax', 'default_discount', 'currency', 'currency_symbol', 'invoice_prefix',
    ];

    protected $casts = [
        'tax' => 'decimal:2',
        'default_discount' => 'decimal:2',
    ];

    public static function get(): self
    {
        return self::first() ?? new self([
            'pharmacy_name' => 'Afzaal Pharmacy',
            'currency' => 'PKR',
            'currency_symbol' => '₨',
            'invoice_prefix' => 'INV',
            'tax' => 0,
            'default_discount' => 0,
        ]);
    }
}
