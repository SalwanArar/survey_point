<?php

use App\Models\Business;
use App\Models\TypeOfSubscription;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('expired_at');

            $business = new Business();
            $typeOfSubscription = new TypeOfSubscription();
            $table->unsignedInteger($business->getForeignKey())->unique();
            $table->unsignedSmallInteger($typeOfSubscription->getForeignKey());

            $table->timestamps();

            $table->foreign($business->getForeignKey())
                ->references('id')
                ->on($business->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign($typeOfSubscription->getForeignKey())
                ->references('id')
                ->on($typeOfSubscription->getTable())
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
