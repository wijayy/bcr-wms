<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supplier extends Model {
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory, Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
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
        'goods',
        'stocks'
    ];

    /**
     * Define a one-to-many relationship with the goods model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<goods>
     */
    public function goods(): HasMany {
        return $this->hasMany(Goods::class);
    }

    public function stocks(): HasManyThrough {
        return $this->hasManyThrough(Stock::class, Goods::class);
    }

    public function shipment(): BelongsTo {
        return $this->belongsTo(Shipment::class);
    }

    public function scopeFilters(Builder $query, array $filters) {
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where("name", "like", "%$search%");
        });

        $query->when($filters["shipment"] ?? false, function ($query, $search) {
            return $query->whereHas("shipment", function ($query) use ($search) {
                $query->where("slug", $search);
            });
        });
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}