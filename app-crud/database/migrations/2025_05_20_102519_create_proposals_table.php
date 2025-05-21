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
        Schema::create('proposals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('ownerId');
            $table->string('projectTitle');
            $table->string('projectNumber')->nullable();
            $table->string('region');
            $table->string('preparedBy');
            $table->string('mainContractor');
            $table->string('reviewStatus')->nullable();
            $table->string('approvedStatus')->nullable();
            $table->string('pathToTP')->nullable();
            $table->timestamps();

            $table->foreign('ownerId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['ownerId']); // Drop the foreign key first
            $table->dropColumn('ownerId');       // Then drop the column
        });
        Schema::dropIfExists('proposals');
    }
};
