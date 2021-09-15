<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('mbadmin_menus')) {
            Schema::Create('mbadmin_menus', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_id')->default(0)->comment('父级菜单ID');
                $table->string('name')->default('')->comment('菜单名称');
                $table->string('i_ico_class')->default('')->nulladle()->comment('图标');
                $table->string('link')->default('')->nulladle()->comment('菜单地址');
                $table->tinyInteger('sort')->default(0)->comment('排序');
                $table->tinyInteger('status')->default(1)->comment('状态');
                $table->tinyInteger('group_id')->default(0)->comment('分组ID');
                $table->softDeletes();//软删除
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
        Schema::dropIfExists('mbadmin_menus');
    }
}
