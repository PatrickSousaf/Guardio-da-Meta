<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('periodo_dados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->integer('periodo');
            $table->integer('total_alunos')->default(0);
            $table->decimal('media_geral', 5, 2)->default(0);
            $table->decimal('infrequencia', 5, 2)->default(0);
            $table->decimal('frequencia', 5, 2)->default(0);
            $table->integer('acima_media_pt')->default(0);
            $table->integer('acima_media_mat')->default(0);
            $table->decimal('percentual_pt', 5, 2)->default(0);
            $table->decimal('percentual_mat', 5, 2)->default(0);
            $table->integer('acima_media_geral')->default(0);
            $table->decimal('percentual_aprovacao_geral', 5, 2)->default(0);
            $table->decimal('media_total', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['curso_id', 'periodo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('periodo_dados');
    }
};