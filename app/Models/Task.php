<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',  // Ajouter 'user_id' dans le $fillable
    ];

    // Relation inverse pour accéder à l'utilisateur associé
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

