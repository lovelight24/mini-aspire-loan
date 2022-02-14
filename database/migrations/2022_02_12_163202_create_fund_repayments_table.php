<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_repayments', function (Blueprint $table) {
            $table->id();
            $table->integer('fund_request_id')->unsigned();
            $table->date('repayment_date');
            $table->float('repayment_amount', 22);
            $table->boolean('is_paid')->default(0)->comment('0: unpaid, 1:paid');
            $table->date('paid_date')->nullable();
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
        Schema::dropIfExists('fund_repayments');
    }
}
