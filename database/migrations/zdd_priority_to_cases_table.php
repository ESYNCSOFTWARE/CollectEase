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
        Schema::table('cases', function (Blueprint $table) {
            $table->enum('priority', ['Low','Medium','High','Critical'])->default('Medium')->after('status');
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('cases', function (Blueprint $table) {
        $table->dropColumn('priority');
    });
}
};
