<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('mbadmin_admins')) {
            Schema::create('mbadmin_admins', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username');
                $table->string('email')->unique()->nullable();
                $table->string('password',60);
                $table->string('fullName')->nullable();
                $table->string('roles')->nullable();
                $table->dateTime('last_login_time')->nullable();
                $table->string('last_login_ip')->nullable();
                $table->tinyInteger('status')->default(1)->comment("1-正常，2-禁用");
                $table->tinyInteger('confirm_email')->default(2)->comment('1-验证，2-没验证');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mbadmin_admins');
    }
}
