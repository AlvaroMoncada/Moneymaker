<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id']; // Permitir asignación masiva de 'user_id'

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
