<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TesteCoresOpcao extends Model
{
    protected $table = 'teste_cores_opcoes';

    protected $fillable = [
        'pergunta_id',
        'letra',
        'texto',
        'cor',
    ];

    protected $casts = [
        'cor' => 'string',
    ];

    /**
     * Get the question that owns the option.
     */
    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(TesteCoresPergunta::class, 'pergunta_id');
    }

    /**
     * Get the responses for the option.
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(TesteCoresResposta::class, 'opcao_id');
    }
}
