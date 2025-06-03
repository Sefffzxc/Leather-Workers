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
        Schema::table('orders', function (Blueprint $table) {
            // First drop the foreign key constraint using the exact constraint name
            $table->dropForeign('orders_product_id_foreign');
            // Then drop the column
            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add the column back
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};