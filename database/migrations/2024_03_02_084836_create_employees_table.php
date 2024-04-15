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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('department_id');
            $table->integer('designation_id');
            $table->integer('category_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('gender');
            $table->string('blood_group');
            $table->string('password');
            $table->date('dob');
            $table->string('email');
            $table->text('phone');
            $table->date('joining_date');
            $table->text('address');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
