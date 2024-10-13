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
        Schema::table('items', function (Blueprint $table) {
            // Add the image column
            $table->string('image');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Remove the image column if rolling back
            $table->dropColumn('image');
        });
    }
};