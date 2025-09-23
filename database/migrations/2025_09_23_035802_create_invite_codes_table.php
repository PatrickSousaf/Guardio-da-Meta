<?php
// database/migrations/XXXX_XX_XX_XXXXXX_create_invite_codes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteCodesTable extends Migration
{
    public function up()
    {
        Schema::create('invite_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('role', ['director', 'management', 'teacher']);
            $table->integer('max_uses')->default(1);
            $table->integer('uses')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invite_codes');
    }
}
