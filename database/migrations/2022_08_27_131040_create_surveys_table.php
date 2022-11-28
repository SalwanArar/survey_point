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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('survey_name', 64);
            $table->tinyText('business_name');
            $table->boolean('customer_info')->default(false);

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
        Schema::dropIfExists('surveys');
    }
};
