<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentCommentRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_comment_replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('text');
            $table->unsignedBigInteger('assessment_comment_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('assessment_comment_id')->references('id')->on('assessment_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('assessment_comment_replies', function ($table) {
            $table->dropForeign('assessment_comment_id');
            $table->dropForeign('user_id');
        });
        Schema::dropIfExists('assessment_comment_replies');
    }
}
