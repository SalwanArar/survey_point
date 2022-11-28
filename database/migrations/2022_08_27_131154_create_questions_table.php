<?php

use App\Models\Survey;
use App\Models\TypeOfQuestion;
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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $survey = new Survey();
            $questionsType = new TypeOfQuestion();
            $table->unsignedInteger($survey->getForeignKey())->index();
            $table->unsignedSmallInteger($questionsType->getForeignKey());

            $table->timestamps();

            $table->foreign($survey->getForeignKey())
                ->references('id')
                ->on($survey->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($questionsType->getForeignKey())
                ->references('id')
                ->on($questionsType->getTable())
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
