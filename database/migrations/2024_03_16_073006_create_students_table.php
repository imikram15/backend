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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('roll_no');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');
            $table->string('gender');
            $table->date('dob');
            $table->string('email');
            $table->string('father_cnic');
            $table->string('b_form');
            $table->string('blood_group');
            $table->string('password');
            $table->text('phone');
            $table->text('address');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
