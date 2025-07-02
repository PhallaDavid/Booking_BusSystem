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
        Schema::table('buses', function (Blueprint $table) {
            $table->boolean('is_promotion')->default(false);
            $table->date('promotion_start_date')->nullable();
            $table->date('promotion_end_date')->nullable();
            $table->decimal('promotion_discount', 5, 2)->nullable(); // Percentage discount (0-100)
            $table->decimal('promotion_price', 8, 2)->nullable(); // Final price after discount
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn([
                'is_promotion',
                'promotion_start_date',
                'promotion_end_date',
                'promotion_discount',
                'promotion_price'
            ]);
        });
    }
};
