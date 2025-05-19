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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('projectTitle');
            $table->string('projectNumber');
            $table->string('region');
            $table->string('preparedBy');
            $table->string('mainContractor');
            $table->string('reviewStatus');
            $table->string('approvedStatus');
            $table->string('pathToTP');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
