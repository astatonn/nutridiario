<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cidade extends Model
{
    protected $fillable = [
        'nome',
        'estado_id',
    ];

    /**
     * Get the state that owns the city.
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    /**
     * Get the users for the city.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the test responses for the city.
     */
    public function testeCoresRespostas(): HasMany
    {
        return $this->hasMany(TesteCoresResposta::class);
    }
}
