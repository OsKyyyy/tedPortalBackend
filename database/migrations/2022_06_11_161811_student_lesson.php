<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_lessons', function (Blueprint $table) {
            $table->id();
            $table->integer('lesson_id');
            $table->integer('student_id');
            $table->float('exam_one')->nullable();
            $table->float('exam_two')->nullable();
            $table->float('exam_three')->nullable();
            $table->float('performance_one')->nullable();
            $table->float('performance_two')->nullable();
            $table->float('project')->nullable();
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('lessons');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_lessons');
    }
}
