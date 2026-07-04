<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('borrowing_code', 30)->unique()->comment('BRW-{YYYYMMDD}-{0001}');
            $table->string('borrower_name', 200);
            $table->string('borrower_department', 200)->nullable();
            $table->string('borrower_phone', 20)->nullable();
            $table->string('borrower_email', 100)->nullable();
            $table->date('borrow_date');
            $table->date('expected_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Performance indexes for search and filter
            $table->index('status');
            $table->index('borrow_date');
            $table->index('expected_return_date');
            $table->index('borrower_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
