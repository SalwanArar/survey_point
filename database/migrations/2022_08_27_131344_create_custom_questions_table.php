<?php

use App\Models\CustomQuestion;
use App\Models\Picture;
use App\Models\Question;
use App\Models\Survey;
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
        Schema::create('custom_questions', function (Blueprint $table) {
            $table->id();

//            $survey = new Survey();
            $question = new Question();
            $customQuestion = new CustomQuestion();
            $picture = new Picture();
//            $table->unsignedInteger($survey->getForeignKey())->index();
            $table->unsignedInteger($question->getForeignKey())->index();
            $table->unsignedInteger($customQuestion->getForeignKey())->nullable();
            $table->unsignedInteger($picture->getForeignKey())->nullable();

            $table->timestamps();

//            $table->foreign($survey->getForeignKey())
//                ->references('id')
//                ->on($survey->getTable())
//                ->cascadeOnUpdate()
//                ->cascadeOnUpdate();
            $table->foreign($question->getForeignKey())
                ->references('id')
                ->on($question->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
            $table->foreign($customQuestion->getForeignKey())
                ->references('id')
                ->on($customQuestion->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
            $table->foreign($picture->getForeignKey())
                ->references('id')
                ->on($picture->getTable())
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unique([$question->getForeignKey(), $customQuestion->getForeignKey()]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_questions');
    }
};
