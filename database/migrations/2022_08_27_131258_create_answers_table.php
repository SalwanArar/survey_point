<?php

use App\Models\Picture;
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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $questions = new Question();
            $picture = new Picture();
            $table->unsignedInteger($questions->getForeignKey())->index();
            $table->unsignedInteger($picture->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($questions->getForeignKey())
                ->references('id')
                ->on($questions->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($picture->getForeignKey())
                ->references('id')
                ->on($picture->getTable())
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
