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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('projectID')->primary();
            $table->string('projectTitle')->unique();
            $table->string('projectNumber')->unique();
            $table->uuid('subContractor');
            $table->timestamps();

            $table->foreign('subContractor')->references('companyID')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Drop Foreign Key
        $table->dropForeign(['subContractor']); 

        //Drop Column
        $table->dropColumn('subContractor');
        Schema::dropIfExists('projects');
    }
};
