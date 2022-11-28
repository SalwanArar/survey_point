<?php

use App\Models\Answer;
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
        Schema::create('answer_translations', function (Blueprint $table) {
            $table->id();
            $table->tinyText('answer_details');
            $table->string('answer_local', 2);

            $answer = new Answer();
            $table->unsignedInteger($answer->getForeignKey())->index();

            $table->timestamps();

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
        Schema::dropIfExists('answer_translations');
    }
};
