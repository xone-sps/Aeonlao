<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckAssessmentSectionQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_assessment_section_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('schema');
            $table->unsignedBigInteger('section_id');
            $table->enum('status', ['checking', 'success'])->default('checking');
            $table->foreign('section_id')
                ->references('id')->on('check_assessment_sections')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_assessment_section_questions', function ($table) {
            $table->dropForeign('section_id');
        });
        Schema::dropIfExists('check_assessment_section_questions');
    }
}
