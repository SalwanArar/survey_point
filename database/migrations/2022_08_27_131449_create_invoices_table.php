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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 30)->unique();
            $table->tinyText('organization_name');
            $table->tinyText('invoice_description');
            $table->tinyText('name');
            $table->char('trn', 15);
            $table->string('location', 2083);
            $table->decimal('vat_rate', 10, 2, true)->default(0.0);
            $table->decimal('price', 10, 2, true)->default(0.0);

            $user = new User();
            $table->unsignedInteger($user->getForeignKey())->nullable();

            $table->timestamp('invoice_date')->useCurrent();
            $table->timestamps();

            $table->foreign($user->getForeignKey())
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
        Schema::dropIfExists('invoices');
    }
};
