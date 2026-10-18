<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Support\ApiPresenter;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $available = $request->query('available');
        if ($available !== null && ! in_array($available, ['true', 'false'], true)) {
            return ApiResponse::error('VALIDATION_ERROR', 'Parameter available harus true atau false.', 400);
        }

        $search = $request->query('q');
        if ($search !== null && ! is_string($search)) {
            return ApiResponse::error('VALIDATION_ERROR', 'Parameter q harus berupa teks.', 400);
        }

        $books = Book::with('category')
            ->when($available === 'true', fn ($q) => $q->where('available_copies', '>', 0))
            ->when($available === 'false', fn ($q) => $q->where('available_copies', '<', 1))
            ->when($search !== null && $search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$search}%")
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))))
            ->orderBy('title')
            ->get();

        return ApiResponse::success(
            $books->map(fn (Book $b) => ApiPresenter::book($b))->values(),
            200,
            ['count' => $books->count()]
        );
    }

    public function show(string $id): JsonResponse
    {
        $book = ctype_digit($id) ? Book::with('category')->find($id) : null;

        if (! $book) {
            return ApiResponse::error('BOOK_NOT_FOUND', 'Buku tidak ditemukan.', 404);
        }

        return ApiResponse::success(ApiPresenter::book($book));
    }
}
