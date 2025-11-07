<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Cviebrock\EloquentSluggable\Sluggable;

class Setting extends Model
{
    use Auditable,Sluggable;

    protected $fillable = [
        'name','slug','description','parameter_type','status'
    ];
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

