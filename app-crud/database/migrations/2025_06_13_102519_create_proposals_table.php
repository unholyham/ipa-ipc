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
            $table->uuid('ownerID');
            $table->uuid('project');
            $table->string('region');
            $table->string('preparedBy');
            // $table->uuid('mainContractor');
            $table->string('reviewStatus')->nullable();
            $table->string('checkedStatus')->nullable();
            $table->string('approvedStatus')->nullable();
            $table->string('remarks')->nullable();
            $table->string('pathToTP')->nullable();
            $table->string('pathToJMS')->nullable();
            $table->timestamps();

            $table->foreign('ownerID')->references('accountID')->on('accounts')->onDelete('cascade');
            $table->foreign('project')->references('projectID')->on('projects')->onDelete('cascade');
            // $table->foreign('mainContractor')->references('companyID')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            //Drop Foreign Keys
            $table->dropForeign(['ownerID']); 
            $table->dropForeign(['project']);
            // $table->dropForeign(['mainContractor']);
            
            

            //Drop Columns
            $table->dropColumn('ownerID');
            $table->dropColumn('project');
            // $table->dropColumn('mainContractor');
        });
        Schema::dropIfExists('proposals');
    }
};
