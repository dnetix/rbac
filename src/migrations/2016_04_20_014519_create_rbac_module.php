<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacModule extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('date_range')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('permission');
            $table->integer('role_id')->unsigned();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('authenticatable_role', function (Blueprint $table) {
            $table->integer('authenticatable_id')->unsigned();
            $table->string('authenticatable_type');
            $table->integer('role_id')->unsigned();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->primary(['authenticatable_id', 'authenticatable_type', 'role_id'], 'authenticatable_id_type_role');
        });
    }

    public function down()
    {
        Schema::drop('authenticatable_role');
        Schema::drop('permission_role');
        Schema::drop('roles');
    }
}
