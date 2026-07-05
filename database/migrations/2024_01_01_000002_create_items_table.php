<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code', 30)->unique()->comment('INV-{CAT_CODE}-{0001}');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('min_stock')->default(5)->comment('Low stock threshold');
            $table->string('location', 200)->nullable();
            $table->enum('condition', ['good', 'fair', 'damaged'])->default('good');
            $table->string('image', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index('stock');
            $table->index('condition');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
