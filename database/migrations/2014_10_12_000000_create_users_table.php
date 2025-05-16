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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branches_id')->unsigned()->index();
            $table->string('name');
            $table->enum('role', ['Admin', 'User', 'Manager'])->default('User');
            $table->string('email')->unique();
            $table->string('username');
            $table->string('mobile')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('original_password')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(1);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('branches_id')->references('id')->on('branches')->onDelete('cascade');
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
}
