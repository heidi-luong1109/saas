<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'employees', function (Blueprint $table){
            $table->id();
            $table->integer('user_id')->default('0');
            $table->integer('employee_id')->default('0');
            $table->date('dob')->nullable();
            $table->integer('department')->default('0');
            $table->integer('designation')->default('0');
            $table->date('joining_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('salary_type')->nullable();
            $table->integer('salary')->default('0');
            $table->integer('created_by')->default('0');
            $table->timestamps();
        }
        );
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
}
