<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use Cviebrock\EloquentSluggable\Sluggable;

class Contact extends Model
{
    use HasFactory,Auditable,Sluggable;

    protected $fillable = [
        'name','email','slug','phone','gender',
        'profile_image','additional_file','merged_into','is_active'
    ];

    public function emails()
    { 
        return $this->hasMany(ContactEmail::class); 
    }
    public function phones()
    { 
        return $this->hasMany(ContactPhone::class); 
    }
    public function customValues()
    { 
        return $this->hasMany(CustomValue::class); 
    }
    public function master()
    { 
        return $this->belongsTo(Contact::class,'merged_into'); 
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }
}
