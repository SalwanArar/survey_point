<?php

use App\Models\AccessAccount;
use App\Models\Review;
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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('comment', 2083);
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Posted', 'Uncompleted']);
            $table->enum('flag', ['Red', 'Yellow', 'Grey']);

            $user = new User();
            $review = new Review();
            $access = new AccessAccount();
            $table->unsignedInteger($user->getForeignKey())->index();
            $table->unsignedInteger($review->getForeignKey())->index();
            $table->unsignedInteger($access->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($user->getForeignKey())
                ->references('id')
                ->on($user->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($review->getForeignKey())
                ->references('id')
                ->on($review->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($access->getForeignKey())
                ->references('id')
                ->on($user->getTable())
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
        Schema::dropIfExists('todos');
    }
};
