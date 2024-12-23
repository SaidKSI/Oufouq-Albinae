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
        Schema::table('company_settings', function (Blueprint $table) {
            // Add missing columns if they don't exist
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_rib')->nullable();
            $table->decimal('capital', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'logo',
                'footer_text',
                'bank_name',
                'bank_account',
                'bank_rib'
            ]);
            // Revert capital back to string if needed
            if (Schema::hasColumn('company_settings', 'capital')) {
                $table->string('capital')->nullable()->change();
            }
        });
    }
};