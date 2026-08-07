<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('medicine_categories')->onDelete('restrict');
            $table->string('name');
            $table->string('manufacturer_name')->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->string('unit')->default('pcs'); // pcs, tablet, strip, bottle
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->date('expiry_date')->nullable();
            $table->integer('reorder_level')->default(10);
            $table->string('description')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
