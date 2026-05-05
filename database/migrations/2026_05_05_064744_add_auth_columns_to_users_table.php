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
            
        $table->string('password')->nullable()->after('email');

        //remember token

        $table->string('remember_token', 100)->nullable()->after('password');

        //email verification timestamp
        $table->timestamp('email_verified_at')->nullable()->after('remember_token');

        //add role column for rbac
        $table->enum('role',['admin','editor','user'])->default('user')->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
        $table->dropColumn(['password','remember_token','email_verified_at','role']);
        });
    }
};
