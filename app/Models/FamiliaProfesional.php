<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FamiliaProfesional extends Model


{

    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'familias_profesionales';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
       
    ];
}
