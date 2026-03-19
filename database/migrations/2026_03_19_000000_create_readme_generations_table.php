<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadmeGenerationsTable extends Migration
{
    public function up()
    {
        Schema::create('readme_generations', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->text('description');
            $table->longText('generated_readme')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('readme_generations');
    }
}
