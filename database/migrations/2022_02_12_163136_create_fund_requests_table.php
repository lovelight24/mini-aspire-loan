<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_requests', function (Blueprint $table) {
            $table->id();
             $table->integer('user_id')->unsigned();
            $table->string('applicant_name');
            $table->float('funding_amount', 22);
            $table->float('interest_rate_weekly', 22)->default(0.00);
            $table->smallInteger('frequency')->default(1)->comment('1: weekly, 2: monthly, 3: yearly');
            $table->smallInteger('tenure');
            $table->boolean('status')->default(0)->comment('0:Request Sent, 1: Request Approved, 2: Request Rejected');
            $table->float('disbursed_amount', 22)->default(0.00);
            $table->date('disbursed_date')->nullable();
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
        Schema::dropIfExists('fund_requests');
    }
}
