<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class InsuranceProvider extends Model
{
    use Sluggable;

    protected $guarded = ['id'];

    protected $casts = [
        'is_sponsored' => 'boolean',
        'pros' => 'array',
        'cons' => 'array',
        'price' => 'decimal:2',
    ];

    //create unique slug
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    //**Define any relationships or custom methods here
    //relationship with policies
    public function policyCategories()
    {
       return $this->belongsToMany(PolicyCategory::class, 'provider_policy_junction', 'provider_id', 'policy_category_id');
    }

    //relationship with states
    public function states()
    {
        return $this->belongsToMany(State::class, 'provider_state_junction', 'provider_id', 'provider_state_id');
    }


    //add image url accessor
    public function getLogoUrlAttribute($value)
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }
}
