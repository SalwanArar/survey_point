<?php

use App\Models\Question;
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
        Schema::create('question_translations', function (Blueprint $table) {
            $table->id();
            $table->tinyText('question_details');
            $table->enum('question_local', ['en','ar','ur','hi']);

            $question = new Question();
            $table->unsignedInteger($question->getForeignKey());

            $table->timestamps();

            $table->foreign($question->getForeignKey())
                ->references('id')
                ->on($question->getTable())
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
        Schema::dropIfExists('question_translations');
    }
};
