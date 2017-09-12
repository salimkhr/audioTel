<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code', function(Blueprint $table) {
            $table->integer('code');
            $table->boolean("active")->default(false);
            $table->boolean("dispo")->default(false);
            $table->string("pseudo");
            $table->text("description");
            $table->integer('annonce_id')->nullable()->unsigned();
            $table->integer('hotesse_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('annonce_id')
                ->references('id')
                ->on('annonce')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('hotesse_id')
                ->references('id')
                ->on('hotesse')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('code', function(Blueprint $table) {
            $table->dropForeign('code_annonce_id_foreign');
            $table->dropForeign('code_hotesse_id_foreign');
        });
        Schema::dropIfExists('code');
    }
}
