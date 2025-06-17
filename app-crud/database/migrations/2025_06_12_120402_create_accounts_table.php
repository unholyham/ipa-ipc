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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('accountID')->primary();
            $table->string('employeeName');
            $table->string('designation')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contactNumber', 20);
            $table->string('verificationStatus');
            $table->string('verificationRejectRemarks')->nullable();
            $table->string('accountStatus');
            // Foreign Keys (UUIDs)
            // 'roles' and 'companies' tables must be created BEFORE 'accounts' table.
            $table->uuid('roleID');
            $table->foreign('roleID')->references('roleID')->on('roles')->onDelete('restrict'); 

            $table->uuid('companyID');
            $table->foreign('companyID')->references('companyID')->on('companies')->onDelete('restrict');
            
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
