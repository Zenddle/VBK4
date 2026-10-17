<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'status', 'requested_at',
        'approved_at', 'due_at', 'returned_at', 'decline_reason',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at'  => 'datetime',
        'due_at'       => 'datetime',
        'returned_at'  => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class)->withTrashed(); }
    public function book() { return $this->belongsTo(Book::class)->withTrashed(); }

    public function scopeActive($q) { return $q->whereIn('status', ['pending', 'approved']); }

    /** Overdue = approved, not returned, and past due date (computed, never stored). */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'approved' && $this->due_at && now()->greaterThan($this->due_at);
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (! $this->due_at || $this->status !== 'approved') return null;
        return (int) now()->startOfDay()->diffInDays($this->due_at->copy()->startOfDay(), false);
    }

    public function getDisplayStatusAttribute(): string
    {
        return $this->is_overdue ? 'overdue' : $this->status;
    }
}
