<?php

use App\Utilities\Enum\EmailSentStatusEnum;
use App\Utilities\Enum\RegistrationStatusEnum;
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
        Schema::create('registered_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->integer('registration_status')->default(RegistrationStatusEnum::REQUESTED);
            $table->integer('is_email_sent')->default(EmailSentStatusEnum::REQUESTED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registered_users');
    }
};
