<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('public_token', 40)->unique();
            $table->string('package', 32);
            $table->string('student_name');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('postcode', 16);
            $table->string('test_centre')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedInteger('amount_pence');
            $table->unsignedInteger('deposit_pence')->default(1000);
            $table->string('status', 20)->default('pending');
            $table->string('stripe_session_id')->nullable()->unique();
            $table->dateTime('hold_expires_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index(['starts_at', 'ends_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
