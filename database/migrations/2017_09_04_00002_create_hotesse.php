<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotesse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotesse', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->smallInteger('active')->default(0);
            $table->boolean('co')->default(false);
            $table->string('tel')->nullable();
            $table->integer('admin_id')->unsigned();
            $table->foreign('admin_id')
                ->references('id')
                ->on('admin')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->rememberToken();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //DB::table('hotesse')->insert(array('name'=>'hotesse1', 'admin_id'=>'1'));
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotesse', function(Blueprint $table) {
            $table->dropForeign('hotesse_admin_id_foreign');
        });
        Schema::dropIfExists('hotesse');
    }
}