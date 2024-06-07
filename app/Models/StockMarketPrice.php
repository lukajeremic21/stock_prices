<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMarketPrice extends Model
{
    use HasFactory;

    protected $table = 'stock_market_prices';

    protected $fillable = [
        'symbol',
        'date',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'after_hours',
        'pre_market',
    ];

    protected $casts = [
        'date' => 'date',
        'open' => 'decimal:2',
        'high' => 'decimal:2',
        'low' => 'decimal:2',
        'close' => 'decimal:2',
        'after_hours' => 'decimal:2',
        'pre_market' => 'decimal:2',
    ];
}
