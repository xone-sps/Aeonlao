<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckAssessmentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_assessment_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('check_assessment_id');
            $table->integer('score')->default('0');
            $table->enum('type', ['institute', 'field_inspector'])->default('institute');
            $table->enum('status', ['checking', 'success'])->default('checking');
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
        Schema::dropIfExists('check_assessment_sections');
    }
}
