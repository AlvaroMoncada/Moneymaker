<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'title',
        'client_id',
        'comission_id',
        'user_id', // Permitir asignación masiva del usuario
    ];

    /**
     * Relación uno a uno inversa con el modelo Client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación uno a uno inversa con el modelo Comission.
     */
    public function comission(): BelongsTo
    {
        return $this->belongsTo(Comission::class);
    }

    /**
     * Relación uno a uno inversa con el modelo User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
