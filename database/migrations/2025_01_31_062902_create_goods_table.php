<?php

use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supplier::class);
            $table->string('code');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('material')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('at_supplier')->default(0);
            $table->text('desc');
            $table->float('weight')->nullable();
            $table->float('us_price')->nullable();
            $table->integer('id_price')->nullable();
            $table->enum('unit', ['pcs', 'set', 'unit', 'prs']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('goods');
    }
};
