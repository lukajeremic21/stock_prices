<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'symbol',
        'name',
    ];

    /**
     * Get the stock market prices for the stock.
     */
    public function stockMarketPrices()
    {
        return $this->hasMany(StockMarketPrice::class, 'symbol', 'symbol');
    }
}
