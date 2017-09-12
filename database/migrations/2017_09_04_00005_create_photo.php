<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('photo');
        Schema::create('photo', function(Blueprint $table) {
            $table->increments('id');
            $table->string('file')->unique();
            $table->integer('code')->nullable();
            $table->foreign('code')
                ->references('code')
                ->on('code')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photo', function(Blueprint $table) {
            $table->dropForeign('photo_code_foreign');
        });
        Schema::dropIfExists('photo');
    }
}
