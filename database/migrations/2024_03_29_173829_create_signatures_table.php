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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('contact');
            $table->string('HcpDesignation')->nullable();
            $table->string('specialty')->nullable();
            $table->string('upload_report')->nullable();
            $table->string('HospitalName')->nullable();
            $table->string('report')->nullable();
            $table->string('city')->nullable();
            $table->string('ai')->nullable();
            $table->string('OtherHcpDesignation')->nullable();
            $table->string('other')->nullable();
            $table->timestamps();
            $table->longText("esign")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signatures');
    }
};
