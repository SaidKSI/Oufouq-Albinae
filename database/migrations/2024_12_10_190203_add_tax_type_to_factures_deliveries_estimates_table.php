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
        Schema::table('factures_deliveries_estimates', function (Blueprint $table) {
            Schema::table('factures', function (Blueprint $table) {
                $table->string('tax_type')->after('tax');
            });
            Schema::table('deliveries', function (Blueprint $table) {
                $table->string('tax_type')->after('tax');
            });
            Schema::table('estimates', function (Blueprint $table) {
                $table->string('tax_type')->after('tax');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures_deliveries_estimates', function (Blueprint $table) {
            Schema::table('factures', function (Blueprint $table) {
                $table->dropColumn('tax_type');
            });
            Schema::table('deliveries', function (Blueprint $table) {
                $table->dropColumn('tax_type');
            });
            Schema::table('estimates', function (Blueprint $table) {
                $table->dropColumn('tax_type');
            });
        });
    }
};