<?php

use App\Models\Device;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('contact_number', 20)->nullable();
            $table->tinyText('comment')->nullable();
            $table->boolean('todo')->default(false);

            $survey = new Survey();
            $device = new Device();
            $table->unsignedInteger($survey->getForeignKey())->index();
            $table->unsignedInteger($device->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($survey->getForeignKey())
                ->references('id')
                ->on($survey->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($device->getForeignKey())
                ->references('id')
                ->on($device->getTable())
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
        Schema::dropIfExists('reviews');
    }
};
