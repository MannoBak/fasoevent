<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Evenement extends Model
{
    use HasFactory;

    protected $fillable= [
        'nom',
        'description',
        'lieu',
        'datedebut',
        'datefin',
        'photo',
        'is_active'
    ];

    public function activite(){
        return $this->belongsTo(Activite::class);
    }

    public function promoteur(){
        return $this->belongsTo(User::class, 'promoteur_id');
    }
}
