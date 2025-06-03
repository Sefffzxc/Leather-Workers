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
            // Remove fields that should be in the pivot table
            // Check if these columns exist before dropping them
            if (Schema::hasColumn('orders', 'quantity')) {
                $table->dropColumn('quantity');
            }
            
            // Remove any other product-specific fields that might exist
            $columnsToRemove = ['price', 'color', 'size', 'product_name'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('product_name')->nullable();
        });
    }
};