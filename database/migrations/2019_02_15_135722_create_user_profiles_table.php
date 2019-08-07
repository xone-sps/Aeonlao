<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {

            $table->increments('id');
            $table->string('public_email')->unique()->nullable();
            $table->string('institute_name')->nullable();
            $table->string('short_institute_name', 80)->nullable();
            $table->text('address')->nullable();
            $table->date('founded')->nullable()->default(null);
            $table->string('phone_number')->nullable();
            $table->string('facebook')->nullable();
            $table->string('googlemap')->nullable();
            $table->string('website')->nullable();
            $table->longText('about')->nullable();

            $table->bigInteger('institute_category_id')->unsigned();
            $table->bigInteger('parent_institute_category_id')->nullable()->unsigned();


            $table->bigInteger('user_id')->unsigned();

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
        Schema::table('user_profiles', function ($table) {
            $table->dropForeign('user_id');
        });
        Schema::dropIfExists('user_profiles');
    }
}
