<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->longText('description');
            $table->integer('section_order');
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id')
                ->references('id')->on('assessments')
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
        Schema::table('assessment_sections', function ($table) {
            $table->dropForeign('assessment_id');
        });
        Schema::dropIfExists('assessment_sections');
    }
}
