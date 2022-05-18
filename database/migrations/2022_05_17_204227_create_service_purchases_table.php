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
        Schema::create('service_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_transaction_id')->constrained('wallet_transactions');
            $table->foreignId('user_id')->constrained('users');
            $table->string('reference');
            $table->string('amount');
            $table->string('service_type');
            $table->string('service_provider');
            $table->string('service_number');
            $table->string('success');
            $table->string('status');
            $table->string('message');
            $table->string('channel')->nullable();
            $table->string('narration');
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
        Schema::dropIfExists('service_purchases');
    }
};
