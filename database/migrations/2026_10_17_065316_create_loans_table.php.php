<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained();
            $t->foreignId('book_id')->constrained();
            // overdue is NOT stored: computed from approved + due_at < now
            $t->enum('status', ['pending', 'approved', 'declined', 'returned'])->default('pending');
            $t->timestamp('requested_at')->nullable();
            $t->timestamp('approved_at')->nullable();
            $t->timestamp('due_at')->nullable();
            $t->timestamp('returned_at')->nullable();
            $t->string('decline_reason')->nullable();
            $t->timestamps();
            $t->index(['user_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('loans'); }
};
