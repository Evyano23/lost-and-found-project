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
            $table->morphs('reportable'); // reportable_id and reportable_type
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // enum ReportType
            $table->text('reason')->nullable();
            $table->string('status'); // enum ReportStatus
            $table->timestamps();
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
