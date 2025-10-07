<?php

namespace Coolycow\LaravelSms\Database\Migrations;

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
        if (!Schema::hasTable('sms')) {
            Schema::create('sms', function (Blueprint $table) {
                $table->id();

                $table->string('phone')->nullable(false)->index();
                $table->text('text')->nullable(false);
                $table->string('sender')->nullable(false);
                $table->string('provider')->nullable(false)->index();

                $table->unsignedBigInteger('provider_id')->nullable()->index();

                $table->string('status')->nullable(false)->index();
                $table->text('status_text')->nullable(false);

                $table->longText('response')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
