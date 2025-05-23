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
        Schema::create('review_images', function (Blueprint $table) {
            $table->id('reviewimg_id');
            $table->unsignedBigInteger('review_id');

            $table->string('image_path');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('review_id')
                ->references('review_id')
                ->on('reviews')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_images');
    }
};
