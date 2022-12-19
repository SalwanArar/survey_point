<?php

use App\Models\Review;
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
        Schema::create('reviewed_customers_info', function (Blueprint $table) {
            $table->enum('info_type', ['name', 'comment', 'birthday', 'contact']);
            $table->tinyText('answer')->nullable();

            $modelReviews = new Review();
            $table->unsignedInteger($modelReviews->getForeignKey());

            $table->timestamps();

            $table->foreign($modelReviews->getForeignKey())
                ->references('id')
                ->on($modelReviews->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->primary([
                $modelReviews->getForeignKey(),
                'info_type'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewed_customers_infos');
    }
};
