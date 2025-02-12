<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name'
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
        'shipments',

    ];

    /**
     * Define a one-to-many relationship with the shipments model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Supplier></Supplier></Supplier>
     */
    public function shipments() {
        return $this->hasMany(Shipment::class, 'marketing_id');
    }

    // Relasi untuk mendapatkan semua goods melalui Shipment
    public function jobs() {
        return $this->hasManyThrough(Job::class, Shipment::class);
    }

    // Relasi untuk mendapatkan semua stocks melalui goods dan suppliers
    public function jobs_detail() {
        return $this->hasManyThrough(Stock::class, Job::class, 'supplier_id', 'job_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeFilters(Builder $query, array $filters) {
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where("name", "like", "%$search%");
        });
    }
}
