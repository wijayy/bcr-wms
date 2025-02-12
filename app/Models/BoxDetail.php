<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoxDetail extends Model {
    /** @use HasFactory<\Database\Factories\BoxDetailFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    /**
     * Define a one-to-one relationship with the shipment model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function box(): BelongsTo {
        return $this->belongsTo(Box::class);
    }
    /**
     * Define a one-to-one relationship with the shipment model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stocks(): BelongsTo {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }

    public function scopeFilters(Builder $query, array $filters) {
        $query->when($filters["goods"] ?? false, function ($query, $search) {
            return $query->whereHas("stocks", function ($query) use ($search) {
                $query->where("goods_id", $search);
            });
        });
    }
}