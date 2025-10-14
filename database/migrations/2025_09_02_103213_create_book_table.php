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
        Schema::create('books', function (Blueprint $table) {   
            $table->id();
            $table->string('name')->index();
            $table->unsignedBigInteger('category_id');
            $table->integer('price');
            $table->date('publication_date');
            $table->integer('edition');
            $table->unsignedBigInteger('author_id');
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('cover')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
