<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->id('loan_id');
            $table->integer('payment_no');
            $table->datetime('payment_date');
            $table->decimal('principal', $precision = 21, $scale = 6);
            $table->decimal('interest', $precision = 21, $scale = 6);
            $table->decimal('balance', $precision = 21, $scale = 6);
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
        Schema::dropIfExists('repayment_schedules');
    }
}
