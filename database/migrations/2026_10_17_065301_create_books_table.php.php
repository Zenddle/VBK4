<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained();
            $t->string('title');
            $t->text('description')->nullable();
            $t->unsignedSmallInteger('year');
            $t->string('cover_path')->nullable();
            $t->unsignedInteger('total_copies')->default(1);
            $t->unsignedInteger('available_copies')->default(1);
            $t->timestamps();
            $t->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('books'); }
};
