<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings') && !Schema::hasColumn('settings', 'default_discount')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->decimal('default_discount', 5, 2)->default(0)->after('tax');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'default_discount')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('default_discount');
            });
        }
    }
};
