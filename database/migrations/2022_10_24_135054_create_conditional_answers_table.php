<?php

use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
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
        Schema::create('conditional_answers', function (Blueprint $table) {
            $table->id();

            $modelQuestion = new Question();
            $table->unsignedInteger($modelQuestion->getForeignKey());
            $modelAnswer = new Answer();
            $table->unsignedInteger($modelAnswer->getForeignKey());
            $modelSurvey = new Survey();
            $table->unsignedInteger($modelSurvey->getForeignKey());

            $table->timestamps();

            $table->foreign($modelQuestion->getForeignKey())
                ->references('id')
                ->on($modelQuestion->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign($modelAnswer->getForeignKey())
                ->references('id')
                ->on($modelAnswer->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign($modelSurvey->getForeignKey())
                ->references('id')
                ->on($modelSurvey->getTable())
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
        Schema::dropIfExists('conditional_answers');
    }
};
