<?php

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
        Schema::create('survey_translations', function (Blueprint $table) {
            $table->id();
            $table->tinyText('survey_phrase');
            $table->char('survey_local', 2);

            $survey = new Survey();
            $table->unsignedInteger($survey->getForeignKey())->index();

            $table->timestamps();

            $table->foreign($survey->getForeignKey())
                ->references('id')
                ->on($survey->getTable())
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
        Schema::dropIfExists('survey_translations');
    }
};
