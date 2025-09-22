<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoDado extends Model
{
    use HasFactory;

    protected $table = 'periodo_dados';

    protected $fillable = [
        'curso_id',
        'periodo',
        'total_alunos',
        'media_geral',
        'infrequencia',
        'frequencia',
        'acima_media_pt',
        'acima_media_mat',
        'percentual_pt',
        'percentual_mat',
        'acima_media_geral',
        'percentual_aprovacao_geral',
        'media_total'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
