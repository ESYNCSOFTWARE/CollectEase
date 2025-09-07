<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('from_collector_id')->nullable()->constrained('collectors')->nullOnDelete();
            $table->foreignId('to_collector_id')->constrained('collectors')->cascadeOnDelete();
            $table->unsignedBigInteger('changed_by')->nullable(); // no FK
            $table->timestamp('assigned_at')->useCurrent();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('assignments');
    }
};
