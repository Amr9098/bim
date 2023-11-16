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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount');
            $table->decimal('remaining_amount')->default(0.00);
            $table->dateTime('due_on');
            $table->decimal('vat');
            $table->boolean('is_vat_inclusive');
            $table->enum('status', ['Paid', 'Outstanding', 'Overdue'])->default('Outstanding');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
