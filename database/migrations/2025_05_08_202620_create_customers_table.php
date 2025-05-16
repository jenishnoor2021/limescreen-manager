<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id')->unsigned()->index();
            $table->unsignedBigInteger('branches_id')->unsigned();
            $table->string('kid_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('address')->nullable();
            $table->string('package')->nullable();
            $table->string('package_amount')->nullable();
            $table->string('advanced')->nullable();
            $table->string('balance')->nullable();
            $table->string('due_date')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->string('verified_at')->nullable();
            $table->text('link')->nullable();
            $table->text('remark')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
