<?php

use App\Models\BoxDetail;
use App\Models\Goods;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Goods::class);
            $table->integer('amount');
            $table->integer('stock');
            $table->enum('type', ['depart', 'stock', 'returned', 'supplier']);
            $table->string('desc');
            $table->string('name')->nullable();
            $table->float('weight')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('stocks');
    }
};
