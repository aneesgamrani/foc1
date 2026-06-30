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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('report_type', ['monthly', 'quarterly', 'biannual', 'annual']);
            $table->unsignedTinyInteger('report_month')->nullable();
            $table->unsignedTinyInteger('report_quarter')->nullable();
            $table->unsignedTinyInteger('biannual_half')->nullable();
            $table->unsignedSmallInteger('report_year');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'report_type', 'report_year'], 'reports_scope_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
