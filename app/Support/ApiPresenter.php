<?php
namespace App\Support;

use App\Models\Book;
use App\Models\Loan;

/** Mengubah model menjadi array JSON yang rapi (tanpa password dan field internal). */
class ApiPresenter
{
    public static function book(Book $b): array
    {
        return [
            'id'               => $b->id,
            'title'            => $b->title,
            'description'      => $b->description,
            'year'             => $b->year,
            'category'         => $b->category ? ['id' => $b->category->id, 'name' => $b->category->name] : null,
            'cover_url'        => $b->cover_url,
            'total_copies'     => $b->total_copies,
            'available_copies' => $b->available_copies,
            'is_available'     => $b->is_available,
        ];
    }

    public static function loan(Loan $l, bool $withUser = false): array
    {
        $data = [
            'id'             => $l->id,
            'status'         => $l->status,
            'display_status' => $l->display_status,
            'is_overdue'     => $l->is_overdue,
            'days_remaining' => $l->days_remaining,
            'requested_at'   => $l->requested_at?->toIso8601String(),
            'approved_at'    => $l->approved_at?->toIso8601String(),
            'due_at'         => $l->due_at?->toIso8601String(),
            'returned_at'    => $l->returned_at?->toIso8601String(),
            'decline_reason' => $l->decline_reason,
            'book'           => $l->book ? ['id' => $l->book->id, 'title' => $l->book->title] : null,
        ];

        if ($withUser && $l->user) {
            $data['user'] = ['id' => $l->user->id, 'name' => $l->user->name, 'nim' => $l->user->nim];
        }

        return $data;
    }
}
