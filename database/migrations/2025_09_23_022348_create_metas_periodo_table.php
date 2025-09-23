<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('metas_periodo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->integer('periodo'); // 1, 2, 3, 4
            $table->string('turma');
            $table->integer('alunos')->default(0);
            $table->decimal('media_geral', 4, 2)->default(0);
            $table->decimal('infrequencia', 5, 2)->default(0);
            $table->decimal('frequencia', 5, 2)->default(0);
            $table->integer('aprovacao_lp')->default(0);
            $table->integer('aprovacao_mt')->default(0);
            $table->integer('aprovacao_geral')->default(0);
            $table->integer('total_aprovados')->default(0);
            $table->decimal('percentual_pt', 5, 2)->default(0);
            $table->decimal('percentual_mat', 5, 2)->default(0);
            $table->decimal('percentual_geral', 5, 2)->default(0);
            $table->decimal('ide_sala', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['curso_id', 'periodo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('metas_periodo');
    }
};
