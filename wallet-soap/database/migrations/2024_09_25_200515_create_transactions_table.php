<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); //credit,debit
            $table->string('status'); //confirmed, expired
            $table->string('token'); //confirmed, expired
            $table->string('session_id'); //payment_intent
            $table->decimal('amount'); //payment_intent
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración del token
            $table->timestamp('confirmed_at')->nullable(); // Fecha de expiración del token
            $table->foreignIdFor(\App\Models\Wallet::class)
                ->constrained('wallets')
                ->nullable()
                ->index('transactions_wallet_id_index')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
