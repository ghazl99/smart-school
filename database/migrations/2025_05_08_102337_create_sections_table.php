<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Classroom_id')->unsigned();
            $table->foreign('Classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
            $table->string('Name');
            $table->integer('Status')->default(1);
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('max_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
