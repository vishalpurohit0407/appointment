<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->dateTime('collection_date')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->enum('rt_pcr', ['0', '1'])->default('0');
            $table->enum('rt_pcr_status', ['0', '1'])->default('0');
            $table->enum('antigens', ['0', '1'])->default('0');
            $table->enum('antigens_status', ['0', '1'])->default('0');
            $table->integer('antigens_count')->nullable();
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

    }
}
