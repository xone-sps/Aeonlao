<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstituteParentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_parent_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('child_id');
            $table->unsignedBigInteger('parent_id');
            $table->timestamps();

            $table->foreign('child_id')
                ->references('id')->on('institute_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')->on('institute_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institute_parent_categories', function ($table) {
            $table->dropForeign('child_id');
            $table->dropForeign('parent_id');
        });
        Schema::dropIfExists('institute_parent_categories');
    }
}
