<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('type_user_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('type_user_id')->references('id')->on('type_users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('user_types', function($table){
            $table->dropForeign(['type_user_id']);
            $table->dropForeign(['admin_id']);
        });
        Schema::dropIfExists('user_types');
    }
}
