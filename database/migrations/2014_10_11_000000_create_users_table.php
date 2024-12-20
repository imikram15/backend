<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('email')->unique(); 
            $table->string('password'); 
            $table->boolean('loginaccess')->default(true); 
            $table->unsignedBigInteger('member_id'); 
            $table->string('member_type');
            $table->unsignedBigInteger('role_id'); 
            $table->timestamps(); 

            $table->foreign('role_id')->references('id')->on('roles'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
