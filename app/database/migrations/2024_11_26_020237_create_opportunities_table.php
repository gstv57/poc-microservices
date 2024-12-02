<?php

use App\Enum\OpportunityStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('url')->unique();
            $table->text('details')->nullable();
            $table->string('business')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('last_send_at')->nullable();
            $table->string('status')->default(OpportunityStatus::PENDING)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['url', 'business', 'user_id', 'last_send_at']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
