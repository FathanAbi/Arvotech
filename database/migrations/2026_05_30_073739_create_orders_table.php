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

            $table->string('invoice_number')->unique();

            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');

            $table->date('event_date');

            $table->text('delivery_address');

            $table->foreignId('package_id')
                ->constrained('catering_packages')
                ->cascadeOnDelete();

            $table->integer('quantity');

            $table->bigInteger('total_price');

            $table->enum('status', [
                'pending_payment',
                'waiting_verification',
                'processing',
                'shipping',
                'completed',
                'rejected'
            ])->default('pending_payment');

            $table->timestamps();
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
