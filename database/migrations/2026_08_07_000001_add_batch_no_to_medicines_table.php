<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('medicines', 'batch_no')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->string('batch_no')->nullable()->after('barcode');
            });
        }

        if (Schema::hasTable('purchase_items') && !Schema::hasColumn('purchase_items', 'batch_no')) {
            Schema::table('purchase_items', function (Blueprint $table) {
                $table->string('batch_no')->nullable()->after('medicine_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('medicines', 'batch_no')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('batch_no');
            });
        }

        if (Schema::hasTable('purchase_items') && Schema::hasColumn('purchase_items', 'batch_no')) {
            Schema::table('purchase_items', function (Blueprint $table) {
                $table->dropColumn('batch_no');
            });
        }
    }
};
