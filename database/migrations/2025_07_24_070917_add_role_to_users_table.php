<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the 'role' column after the 'remember_token' column.
            // It will have two possible values: 'admin' or 'employee'.
            // The default for any new user will be 'employee'.
            $table->enum('role', ['admin', 'employee'])->default('employee')->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This allows the migration to be undone if needed.
            $table->dropColumn('role');
        });
    }
};
