<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;

class Job extends Model {
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory, Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'no_job',
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
        'box'
    ];

    /**
     * Define a one-to-one relationship with the shipment model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipment(): BelongsTo {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Define a one-to-one relationship with the shipment model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function box(): HasMany {
        return $this->hasMany(Box::class);
    }

    public function box_detail(): HasManyThrough {
        return $this->hasManyThrough(BoxDetail::class, Box::class)->orderBy('boxes.no_box', 'asc');
    }

    protected $table = 'job';

    public function getRouteKeyName() {
        return 'slug';
    }

    public function casts() {
        return [
            "shipping_date" => "date:Y-m-d"
        ];
    }

    public function scopeFilters(Builder  $query, array $filters) {
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where("no_job", "like", "%$search%");
        });

        $query->when($filters["shipment"] ?? false, function ($query, $search) {
            return $query->whereHas("shipment", function ($query) use ($search) {
                $query->where("slug", $search);
            });
        });
    }

    /**
     * Boot the model
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function ($job) {
            if ($job->status == 1) {
                throw new \Exception('A Done Job cannot be deleted.');
            }
        });

        static::creating(function ($job) {
            // Cek apakah shipment memiliki job dengan status "progress"
            $existingJob = self::where('shipment_id', $job->shipment_id)
                ->where('status', 'progress')
                ->exists();

            if ($existingJob) {
                throw new \Exception('Cannot create a new job for this shipment because there is already a job in progress.');
            }
        });
    }
}
