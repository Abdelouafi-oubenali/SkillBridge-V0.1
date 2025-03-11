<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('course_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();    
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
    
            $table->primary(['course_id', 'tag_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('course_tag');
    }
};
