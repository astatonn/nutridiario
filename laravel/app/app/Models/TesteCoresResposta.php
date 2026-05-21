<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TesteCoresResposta extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cidade_id',
        'opcao_id',
        'session_id',
    ];

    /**
     * Get the city for the response.
     */
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    /**
     * Get the option that was selected.
     */
    public function opcao(): BelongsTo
    {
        return $this->belongsTo(TesteCoresOpcao::class, 'opcao_id');
    }
}
