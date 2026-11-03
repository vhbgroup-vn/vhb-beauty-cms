<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('static_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('published')->default(false)->index();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->longText('html_content')->nullable();
            $table->timestamps();
        });

        Schema::create('static_blockables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('static_block_id')->constrained()->cascadeOnDelete();
            $table->morphs('static_blockable');
            $table->unsignedInteger('sort_order')->default(0);
            $table->unique(['static_block_id', 'static_blockable_type', 'static_blockable_id'], 'static_blockables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('static_blockables');
        Schema::dropIfExists('static_blocks');
    }
};
