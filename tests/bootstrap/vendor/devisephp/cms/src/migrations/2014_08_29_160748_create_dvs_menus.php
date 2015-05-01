<?php

use Illuminate\Database\Migrations\Migration;

class CreateDvsMenus extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dvs_menus', function($table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->integer('translated_from_menu_id')->default(0);
            $table->string('name', 255);
            $table->text('links')->nullable();
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dvs_menus');
    }

}