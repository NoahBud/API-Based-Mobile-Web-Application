<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'name', 
        'address',
        'etat',
        'longitude',
        'latitude'
    ];

    public function copies() : HasMany {
        return $this->HasMany(Copy::class);
    } 
}
