<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'invoice_payments', function (Blueprint $table){
            $table->id();
            $table->string('transaction')->nullable();
            $table->integer('invoice');
            $table->float('amount', 15, 2);
            $table->date('date');
            $table->integer('payment_method');
            $table->string('payment_type')->nullable();
            $table->text('notes')->nullable();
            $table->text('receipt')->nullable();
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
        Schema::dropIfExists('invoice_payments');
    }
}
