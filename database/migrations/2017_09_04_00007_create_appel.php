<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appel', function(Blueprint $table) {
            $table->increments('id');
            $table->dateTime('debut');
            $table->integer('durée');
            $table->integer('tarif');
            $table->integer('code');
            $table->foreign('code')
                ->references('code')
                ->on('code')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')
                ->references('id')
                ->on('client')
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
        Schema::table('appel', function(Blueprint $table) {
            $table->dropForeign('appel_code_foreign');
            $table->dropForeign('appel_client_id_foreign');
        });
        Schema::dropIfExists('appel');

    }
}
