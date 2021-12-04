<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('delivery_mode')->nullable(false);
            $table->string('invoice_name')->nullable(false);
            $table->string('invoice_zip_code')->nullable(false);
            $table->string('invoice_city')->nullable(false);
            $table->string('invoice_address')->nullable(false);
            $table->string('delivery_name')->nullable(false);
            $table->string('delivery_zip_code')->nullable(false);
            $table->string('delivery_city')->nullable(false);
            $table->string('delivery_address')->nullable(false);
            $table->string('status')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
