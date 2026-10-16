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
        'comission',
        'sell_date',
        'next_visit',
        'client_id',
        'user_id', // Permitir asignación masiva del usuario
    ];

    protected $casts = [
        'sell_date' => 'date:Y-m-d',
        'next_visit' => 'date:Y-m-d',
    ];

    /**
     * Relación uno a uno inversa con el modelo Client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación uno a uno inversa con el modelo User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
