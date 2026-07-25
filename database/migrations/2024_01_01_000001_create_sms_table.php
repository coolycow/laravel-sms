<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->index();
            $table->text('text');
            $table->string('sender');
            $table->string('provider')->index();
            $table->unsignedBigInteger('provider_id')->nullable()->index();
            $table->string('status')->index();
            $table->text('status_text');
            $table->json('params')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
