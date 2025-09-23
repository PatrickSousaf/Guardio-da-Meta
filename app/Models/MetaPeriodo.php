<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaPeriodo extends Model
{
    use HasFactory;

    protected $table = 'metas_periodo';

    protected $fillable = [
        'curso_id',
        'periodo',
        'turma',
        'alunos',
        'media_geral',
        'infrequencia',
        'frequencia',
        'aprovacao_lp',
        'aprovacao_mt',
        'aprovacao_geral',
        'total_aprovados',
        'percentual_pt',
        'percentual_mat',
        'percentual_geral',
        'ide_sala'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
