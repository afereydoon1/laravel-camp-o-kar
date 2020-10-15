<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('price');
            $table->unsignedInteger('original_price');
            $table->unsignedInteger('discounted_price');
            $table->unsignedBigInteger('user_id');
            $table->morphs('paymentable');
            $table->string('authority')->nullable();
            $table->string('ref_id')->nullable();
            $table->longText('extra_details')->nullable();
            $table->boolean('is_payed')->default(false);
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
        Schema::dropIfExists('payments');
    }
}
