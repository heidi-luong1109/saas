<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type', 20);
            $table->string('avatar', 100)->default('');
            $table->string('lang', 100);
            $table->integer('created_by')->default(0);
            $table->integer('plan')->nullable();
            $table->date('plan_expire_date')->nullable();
            $table->integer('delete_status')->default(1);
            $table->integer('is_active')->default(1);
            $table->integer('default_pipeline')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
