<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained('borrowings')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->enum('condition_before', ['good', 'fair', 'damaged'])->default('good');
            $table->enum('condition_after', ['good', 'fair', 'damaged'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Composite index for fast lookup
            $table->index(['borrowing_id', 'item_id']);
            $table->index('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};
