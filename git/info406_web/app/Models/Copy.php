<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Copy extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_exemplaire', 
        'etat', 
        'disponibilite', 
        'book_id',
        'box_id'
    ];

    public function book() : BelongsTo {
        return $this->belongsTo(Book::class);
    }

    public function box() : BelongsTo{
        return $this->belongsTo(Box::class);
    }
}
