<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->bigIncrements('id'); // BIGINT AUTO_INCREMENT PRIMARY KEY
            $table->string('name', 100)->unique(); // VARCHAR(100) NOT NULL UNIQUE
            $table->string('code', 10)->unique()->nullable(); // VARCHAR(10) UNIQUE (optional, so nullable)
            $table->boolean('status')->default(1); // TINYINT(1) DEFAULT 1
            $table->timestamp('created_at')->useCurrent(); // TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // TIMESTAMP ON UPDATE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
