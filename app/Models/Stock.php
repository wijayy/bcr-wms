<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stock extends Model {
    /** @use HasFactory<\Database\Factories\StockFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Define a one-to-one relationship with the goods model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods(): BelongsTo {
        return $this->belongsTo(Goods::class);
    }
    /**
     * Define a one-to-one relationship with the goods model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function box_detail(): HasOne {
        return $this->hasOne(BoxDetail::class, 'id', 'stock_id');
    }
}