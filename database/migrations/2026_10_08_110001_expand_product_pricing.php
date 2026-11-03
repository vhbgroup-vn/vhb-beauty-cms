<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('price')->nullable()->change();
            $table->string('currency', 3)->default('VND')->after('price');
            $table->unsignedInteger('price_min')->nullable()->after('currency');
            $table->unsignedInteger('price_max')->nullable()->after('price_min');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['currency', 'price_min', 'price_max']);
        });
    }
};
