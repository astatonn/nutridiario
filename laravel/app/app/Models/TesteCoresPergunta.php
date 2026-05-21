<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TesteCoresPergunta extends Model
{
    protected $fillable = [
        'ordem',
        'texto',
    ];

    /**
     * Get the options for the question.
     */
    public function opcoes(): HasMany
    {
        return $this->hasMany(TesteCoresOpcao::class, 'pergunta_id');
    }
}
