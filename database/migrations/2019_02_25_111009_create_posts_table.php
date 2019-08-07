<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['pending', 'close', 'open'])->default('open');
            $table->enum('type', ['news', 'remark', 'activity', 'scholarship'])->default('news');
            $table->string('title' );
            $table->string('image');
            $table->longText('description');
            $table->text('place')->nullable()->default(null);
            $table->string('scholarship_type')->nullable()->default(null);
            $table->timestamp('start_date')->nullable()->default(null);
            $table->timestamp('deadline')->nullable()->default(null);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->double('view')->default(0);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
    //ALTER TABLE posts ADD INDEX (start_date);

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function ($table) {
            $table->dropForeign('user_id');
        });
        Schema::dropIfExists('posts');
    }
}
