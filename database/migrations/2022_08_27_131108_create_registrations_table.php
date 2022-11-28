<?php

use App\Models\Logo;
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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->boolean('name')->default(false);
            $table->boolean('birthdate')->default(false);
            $table->boolean('gender')->default(false);
            $table->boolean('phone_number')->default(false);
            $table->boolean('nationality')->default(false);
            $table->boolean('residence_state')->default(false);
            $table->boolean('comment')->default(false);

            $user = new User();
            $logo = new Logo();
            $table->unsignedInteger($user->getForeignKey())->index();
            $table->unsignedInteger($logo->getForeignKey())->nullable();

            $table->timestamps();

            $table->foreign($user->getForeignKey())
                ->references('id')
                ->on($user->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($logo->getForeignKey())
                ->references('id')
                ->on($logo->getTable())
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
        Schema::dropIfExists('registrations');
    }
};
