<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckAssessmentFieldInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_assessment_field_inspectors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['checking', 'close', 'success'])->default('checking');
            $table->unsignedBigInteger('check_assessment_id');
            $table->foreign('check_assessment_id')
                ->references('id')->on('check_assessments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('check_user_id');//selected
            $table->foreign('check_user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('field_inspector_id');
            $table->foreign('field_inspector_id')
                ->references('id')->on('users')
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
        Schema::table('check_assessment_field_inspectors', function ($table) {
            $table->dropForeign('check_assessment_id');
            $table->dropForeign('check_user_id');
            $table->dropForeign('field_inspector_id');
        });
        Schema::dropIfExists('check_assessment_field_inspectors');
    }
}
