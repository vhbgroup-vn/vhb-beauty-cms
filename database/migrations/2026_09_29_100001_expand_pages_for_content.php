<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('template')->default('standard');
            $table->text('excerpt')->nullable();
            $table->json('blocks')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('show_in_navigation')->default(false);
            $table->string('navigation_label')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'template', 'excerpt', 'blocks', 'seo_title', 'seo_description',
                'sort_order', 'show_in_navigation', 'navigation_label',
            ]);
        });
    }
};
