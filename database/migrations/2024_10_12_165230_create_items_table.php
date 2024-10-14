<?php

use App\Enums\ItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submit_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('take_id')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->enum('type', ['lost', 'found']);
            $table->boolean('verified')->default(false);
            $table->enum('status', ['not taken', 'taken'])->default(ItemStatus::NOTTAKEN->value);
            $table->string('title');
            $table->string('location');
            $table->string('description');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
