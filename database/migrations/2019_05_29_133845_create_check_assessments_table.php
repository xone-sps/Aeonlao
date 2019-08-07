<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_assessments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['checking', 'close', 'success'])->default('checking');

            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id')
                ->references('id')->on('assessments')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
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

        Schema::table('check_assessments', function ($table) {
            $table->dropForeign('assessment_id');
            $table->dropForeign('user_id');
        });
        Schema::dropIfExists('check_assessments');
    }
}
