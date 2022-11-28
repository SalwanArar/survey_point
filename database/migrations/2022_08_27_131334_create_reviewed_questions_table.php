<?php

use App\Models\Answer;
use App\Models\Question;
use App\Models\Review;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('reviewed_questions', function (Blueprint $table) {
            $table->id();

            $review = new Review();
            $question = new Question();
            $answer = new Answer();
            $table->unsignedInteger($review->getForeignKey())->index();
            $table->unsignedInteger($question->getForeignKey());
            $table->unsignedInteger($answer->getForeignKey());

            $table->timestamps();

            $table->unique([
                $review->getForeignKey(),
                $question->getForeignKey(),
                $answer->getForeignKey(),
            ]);

            $table->foreign($review->getForeignKey())
                ->references('id')
                ->on($review->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($question->getForeignKey())
                ->references('id')
                ->on($question->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($answer->getForeignKey())
                ->references('id')
                ->on($answer->getTable())
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
        Schema::dropIfExists('reviewed_questions');
    }
};
