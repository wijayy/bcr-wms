<?php

use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('job', function (Blueprint $table) {
            $table->id();
            // $table->foreignIdFor(Supplier::class);
            $table->foreignIdFor(Shipment::class);
            $table->string('no_job')->unique();
            $table->string('slug')->unique();
            $table->string('destination')->nullable();
            $table->date('shipping_date')->nullable();
            $table->boolean("status")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('job');
    }
};
