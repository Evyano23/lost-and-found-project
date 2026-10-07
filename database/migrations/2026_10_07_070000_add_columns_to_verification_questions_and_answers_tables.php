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
        if (! Schema::hasColumn('verification_questions', 'item_id')) {
            Schema::table('verification_questions', function (Blueprint $table) {
                $table->foreignId('item_id')->constrained()->cascadeOnDelete();
                $table->string('question', 500);
            });
        }

        if (! Schema::hasColumn('claim_verification_answers', 'claim_id')) {
            Schema::table('claim_verification_answers', function (Blueprint $table) {
                $table->foreignId('claim_id')->constrained()->cascadeOnDelete();
                $table->foreignId('verification_question_id')->constrained()->cascadeOnDelete();
                $table->text('answer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('claim_verification_answers', 'claim_id')) {
            Schema::table('claim_verification_answers', function (Blueprint $table) {
                $table->dropForeign(['claim_id']);
                $table->dropForeign(['verification_question_id']);
                $table->dropColumn(['claim_id', 'verification_question_id', 'answer']);
            });
        }

        if (Schema::hasColumn('verification_questions', 'item_id')) {
            Schema::table('verification_questions', function (Blueprint $table) {
                $table->dropForeign(['item_id']);
                $table->dropColumn(['item_id', 'question']);
            });
        }
    }
};
