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
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('name')->comment('Name of the image');
            $table->string('extension')->comment('File extension of the image');
            $table->string('original_name')->comment('Name of the image');
            $table->string('size')->comment('Size of the image file');
            $table->string('path')->comment('Path to the image file');
            // $table->string('url')->comment('URL to access the image');
            // $table->string('alt_text')->nullable()->comment('Alternative text for the image');
            // $table->string('caption')->nullable()->comment('Caption for the image');
            // $table->string('credit')->nullable()->comment('Credit for the image source');
            // $table->string('source')->nullable()->comment('Source of the image');
            // $table->string('type')->default('image')->comment('Type of the image, default is "image"');
            // $table->boolean('is_active')->default(true)->comment('Indicates if the image is active');
            // $table->boolean('is_featured')->default(false)->comment('Indicates if the image is featured');
            // $table->unsignedBigInteger('user_id')->nullable()->comment('ID of the user who uploaded the image');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->comment('Foreign key to users table');
            // $table->unsignedBigInteger('gallery_id')->nullable()->comment('ID of the gallery the image belongs to');
            // $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('set null')->comment('Foreign key to galleries table');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
