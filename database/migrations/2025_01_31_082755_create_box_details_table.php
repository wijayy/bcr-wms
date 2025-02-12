<?php

use App\Models\Box;
use App\Models\Goods;
use App\Models\Stock;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('box_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Box::class);
            $table->foreignIdFor(Stock::class);
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('box_details');
    }
};