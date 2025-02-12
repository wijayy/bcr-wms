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

class Shipment extends Model {
    /** @use HasFactory<\Database\Factories\ShipmentFactory> */
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
    protected $with = ['supplier', 'jobs', 'goods'];

    /**
     * Define a one-to-many relationship with the goods model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<goods>
     */
    public function supplier(): HasMany {
        return $this->hasMany(Supplier::class);
    }

    public function goods(): HasManyThrough {
        return $this->hasManyThrough(Goods::class, Supplier::class);
    }

    /**
     * Define a one-to-many relationship with the job model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs(): HasMany {
        return $this->hasMany(Job::class);
    }

    public function box_details(): HasManyThrough {
        return $this->hasManyThrough(
            BoxDetail::class,   // Model tujuan (BoxDetail)
            Job::class,         // Model perantara pertama (Job)
            'shipment_id',      // Foreign key di model perantara pertama (Job)
            'box_id',           // Foreign key di model tujuan (BoxDetail)
            'id',               // Local key di model saat ini (Shipment)
            'id'                // Local key di model perantara pertama (Job)
        );    // Menyambungkan ke model perantara kedua (Box)
    }

    // public function job_details(): HasManyThrough {
    //     return $this->hasManyThrough(Job_Detail::class, Job::class);
    // }

    /**
     * Define a one-to-one relationship with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<job>
     */
    public function marketing(): BelongsTo {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function scopeFilters(Builder $query, array $filters) {
        $query->when($filters["search"] ?? false, function ($query, $search) {
            return $query->where("name", "like", "%$search%");
        });

        $query->when($filters["marketing"] ?? false, function ($query, $search) {
            return $query->whereHas("marketing", function ($query) use ($search) {
                $query->where("slug", $search);
            });
        });

        // Filter based on the logged-in user's role
        if (!Auth::user()->is_admin) {
            $query->where('marketing_id', Auth::user()->id);
        }
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    /**
     * Boot the model and handle the deleting event.
     */
    protected static function boot() {
        parent::boot();

        // Cascade delete jobs when a shipment is deleted
        static::deleting(function ($shipment) {
            $sh = $shipment->jobs ?? collect();
            $sh->each(function ($job) {
                $job->delete();
            });
        });
    }
}