<?php

use Domain\Enum\ArticleProviderCode;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->string('url');
            $table->string('image_url', 500)->nullable();
            $table->foreignUuid('author_id')->nullable()->constrained('authors');
            $table->foreignUuid('source_id')->constrained('sources');
            $table->foreignUuid('category_id')->constrained('categories');
            $table->timestamp('published_at');
            $table->enum('provider_code', ArticleProviderCode::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
