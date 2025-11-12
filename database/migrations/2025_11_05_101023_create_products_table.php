<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  //autoincreament 
            $table->string('name');   // varchar
            $table->text('description')->nullable();   //longer text
            $table->decimal('price', 10, 2)->default(0); 
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);

            //foreign key  
            $table->foreignId(column: 'user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};