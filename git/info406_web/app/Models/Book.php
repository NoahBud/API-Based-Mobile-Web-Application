<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'ISBN', 
        'name', 
        'genre', 
        'description',
        'editor_id'
    ];

    //protected $with = ['authors']; // pour charger les auteurs en JSON au cas ou

    public function copies() : HasMany {
        return $this->hasMany(Copy::class);
    } 

    public function editor() : BelongsTo {
        return $this->belongsTo(Editor::class);
    } 

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class);
    }
}

