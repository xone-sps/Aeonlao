<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('schema');
            $table->integer('question_order');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')
                ->references('id')->on('assessment_sections')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->softDeletes();
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
        Schema::table('section_questions', function ($table) {
            $table->dropForeign('section_id');
        });
        Schema::dropIfExists('section_questions');
    }
}
