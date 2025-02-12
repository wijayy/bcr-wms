<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goods extends Model {
    /** @use HasFactory<\Database\Factories\GoodsFactory> */
    use HasFactory, Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'code',
                'onUpdate' => true
            ]
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];


    /**
     * The attributes use for eager loading.
     *
     * @var list<string>
     */
    protected $with = [
        'stocks',
    ];

    /**
     * Define a one-to-many relationship with the stock model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks(): HasMany {
        return $this->hasMany(Stock::class);
    }


    /**
     * Define a one-to-one relationship with the supplier model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Define a one-to-one relationship with the supplier model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function box(): BelongsTo {
        return $this->belongsTo(Box::class);
    }

    public function scopeFilters(Builder  $query, array $filters) {
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where("code", "like", "%$search%");
        });

        $query->when($filters["supplier"] ?? false, function ($query, $search) {
            return $query->whereHas("supplier", function ($query) use ($search) {
                $query->where("slug", $search);
            });
        });
        $query->when($filters["shipment"] ?? false, function ($query, $search) {
            return $query->whereHas("supplier", function ($query) use ($search) {
                $query->where("shipment_id", $search);
            })->orderBy('shipment_id');
        });
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}