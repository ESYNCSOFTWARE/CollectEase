<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('escalations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('escalated_to')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // no FK
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('escalations');
    }
};
