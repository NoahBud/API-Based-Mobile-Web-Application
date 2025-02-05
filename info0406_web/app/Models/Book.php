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

    public function copies() : HasMany {
        return $this->HasMany(Copy::class);
    } 

    public function editor() : BelongsTo {
        return $this->BelongsTo(Editor::class);
    } 

    public function authors() : BelongsToMany {
        return $this->BelongsToMany(Author::class);
    }
}

