<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opportunity_request_scraping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_scraping_id')->index();
            $table->foreign('request_scraping_id')->references('id')->on('request_scrapings')->onDelete('cascade');
            $table->unsignedBigInteger('opportunity_id')->index();
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity_request_scraping');
    }
};
