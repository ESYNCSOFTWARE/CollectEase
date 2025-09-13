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
        Schema::create('statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',length:50);
            $table->enum('type', ['Case', 'Assignment']);
            $table->string('code',length:20);
            $table->string('description');
            $table->string('color',length:7);
            $table->tinyInteger('sort_order');
            $table->boolean('is_default')->default(0);
            $table->timestamp('created_at')->useCurrent(); 
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
