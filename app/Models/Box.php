<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model {
    /** @use HasFactory<\Database\Factories\BoxFactory> */
    use HasFactory, Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'customSlug',
                'onUpdate' => true
            ]
        ];
    }

    public function getCustomSlugAttribute() {
        $slug = $this->job->slug . '-' . $this->no_box;
        if ($this->prefix) {
            $slug .= $this->prefix;
        }
        return $slug;
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
        'box_detail',
    ];

    /**
     * Define a one-to-one relationship with the shipment model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job(): BelongsTo {
        return $this->belongsTo(Job::class);
    }

    /**
     * Define a one-to-many relationship with the job_detail model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function box_detail(): HasMany {
        return $this->hasMany(BoxDetail::class);
    }


    public function getRouteKeyName() {
        return 'slug';
    }
}