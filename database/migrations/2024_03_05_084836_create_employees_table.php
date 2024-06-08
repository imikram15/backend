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
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('gender');
            $table->string('blood_group');
            $table->date('dob');
            $table->string('email');
            $table->text('phone');
            $table->date('joining_date');
            $table->text('address');
            $table->string('image')->nullable();
            $table->timestamps();

             $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
             $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
             $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
