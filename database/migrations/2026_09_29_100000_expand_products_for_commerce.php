<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique();
            $table->string('brand')->nullable()->index();
            $table->string('product_type')->default('simple')->index();
            $table->unsignedInteger('sale_price')->nullable();
            $table->dateTime('sale_starts_at')->nullable();
            $table->dateTime('sale_ends_at')->nullable();
            $table->string('stock_status')->default('in_stock')->index();
            $table->boolean('manage_stock')->default(true);
            $table->integer('stock_quantity')->nullable();
            $table->string('backorders')->default('no');
            $table->boolean('sold_individually')->default(false);
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('shipping_class')->nullable();
            $table->json('tags')->nullable();
            $table->json('gallery')->nullable();
            $table->json('attributes')->nullable();
            $table->json('variations')->nullable();
            $table->text('purchase_note')->nullable();
            $table->boolean('reviews_enabled')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn([
                'sku', 'brand', 'product_type', 'sale_price', 'sale_starts_at', 'sale_ends_at',
                'stock_status', 'manage_stock', 'stock_quantity', 'backorders', 'sold_individually',
                'weight', 'length', 'width', 'height', 'shipping_class', 'tags', 'gallery',
                'attributes', 'variations', 'purchase_note', 'reviews_enabled',
            ]);
        });
    }
};
