<?php

use App\Models\User;
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
        Schema::create('online_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('survey_url', 2048);
            $table->boolean('status')->default(true);

            $user = new User();
            $table->unsignedInteger($user->getForeignKey())->index();

            $table->timestamp('expired_at')->useCurrent();
            $table->timestamps();

            $table->foreign($user->getForeignKey())
                ->references('id')
                ->on($table->getTable())
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
        Schema::dropIfExists('online_surveys');
    }
};
