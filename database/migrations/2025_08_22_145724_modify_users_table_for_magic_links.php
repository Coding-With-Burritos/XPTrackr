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
            // Rename 'name' column to 'username'
            $table->renameColumn('name', 'username');
            
            // Drop columns not needed for magic links
            $table->dropColumn(['email_verified_at', 'password']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the original columns
            $table->renameColumn('username', 'name');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
        });
    }
};
