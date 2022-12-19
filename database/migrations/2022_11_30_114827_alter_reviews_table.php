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
        $modelReview = new Review();
        Schema::dropColumns($modelReview->getTable(), [
            'contact_number',
            'comment',
            'name',
        ]);

        Schema::table($modelReview->getTable(), function (Blueprint $table) {
            $table->timestamp('reviewed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
};
