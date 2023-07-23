<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration{

    public function up(){
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id')->on('invoices')->onDelete('Cascade');
            $table->string('invoice_number', 50);
            $table->string('product', 50);
            $table->string('section', 999);
            $table->string('status', 50);
            $table->string('value_status');
            $table->text('note')->nullable();
            $table->date('Payment_Date')->nullable();
            $table->string('user', 255);
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('invoice_details');
    }
}