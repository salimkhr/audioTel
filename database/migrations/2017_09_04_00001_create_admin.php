<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admin');
        Schema::create('admin', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('role')->default("admin");
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            //DB::table('admins')->insert(array('name'=>'admin1','email'=>'mail','password'=>'password'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
