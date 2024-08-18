<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('billplz_id');
            $table->string('collection_id');
            $table->unsignedBigInteger('campaign_id');
            $table->string('email');
            $table->string('name');
            $table->string('phone');
            $table->integer('status')->default(1);
            $table->integer('amount');
            $table->integer('discount');
            $table->integer('quantity');
            $table->integer('fee');
            $table->integer('shipping')->nullable();
            $table->string('variations')->nullable();
            $table->string('paid');
            $table->string('paid_at');
            $table->string('address');
            $table->string('postcode');
            $table->string('state');
            $table->string('tracking_number')->nullable();
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->index(['campaign_id','billplz_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
