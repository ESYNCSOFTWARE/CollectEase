<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('debtor_name');
            $table->string('debtor_contact')->nullable();
            $table->decimal('amount_due', 12, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->foreignId('case_type_id')->constrained('case_types')->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('status', 20)->default('New'); // simpler than ENUM
            $table->foreignId('assigned_collector_id')->nullable()->constrained('collectors')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cases');
    }
};
