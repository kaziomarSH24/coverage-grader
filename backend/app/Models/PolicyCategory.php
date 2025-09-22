<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PolicyCategory extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    //add image url accessor
    public function getLogoUrlAttribute($value)
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }
}
