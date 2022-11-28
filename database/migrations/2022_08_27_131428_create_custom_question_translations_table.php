<?php

use App\Models\CustomQuestion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('custom_question_translations', function (Blueprint $table) {
            $table->id();
            $table->tinyText('question_details');
            $table->enum('question_local', ['en', 'ar', 'ur', 'hi',]);

            $customQuestion = new CustomQuestion();
            $table->unsignedInteger($customQuestion->getForeignKey())->index();

            $table->timestamps();

            $table->foreign($customQuestion->getForeignKey())
                ->references('id')
                ->on($customQuestion->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_question_translations');
    }
};
