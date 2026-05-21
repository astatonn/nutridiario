<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    protected $fillable = [
        'nome',
        'uf',
    ];

    /**
     * Get the cities for the state.
     */
    public function cidades(): HasMany
    {
        return $this->hasMany(Cidade::class);
    }
}
