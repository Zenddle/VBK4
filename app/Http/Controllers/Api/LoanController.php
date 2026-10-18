<?php
namespace App\Http\Controllers\Api;

use App\Exceptions\LoanException;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Services\LoanService;
use App\Support\ApiPresenter;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    private const STATUSES = ['pending', 'approved', 'declined', 'returned'];

    /** Mahasiswa mengajukan peminjaman -> status pending (menunggu persetujuan admin). */
    public function store(Request $request, LoanService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'book_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('VALIDATION_ERROR', 'Data yang dikirim tidak valid.', 400, $validator->errors()->toArray());
        }

        $book = Book::find($request->input('book_id'));
        if (! $book) {
            return ApiResponse::error('BOOK_NOT_FOUND', 'Buku tidak ditemukan.', 404);
        }

        try {
            $loan = $service->request($request->user(), $book);
        } catch (LoanException $e) {
            return ApiResponse::error($e->errorCode, $e->getMessage(), 409);
        }

        return ApiResponse::success(ApiPresenter::loan($loan->load('book')), 201);
    }

    /** Riwayat milik sendiri, bisa difilter ?status= */
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');
        if ($status !== null && ! in_array($status, self::STATUSES, true)) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Status harus salah satu dari: ' . implode(', ', self::STATUSES) . '.',
                400
            );
        }

        $loans = $request->user()->loans()->with('book')
            ->when($status !== null, fn ($q) => $q->where('status', $status))
            ->latest()
            ->orderByDesc('id')
            ->get();

        return ApiResponse::success(
            $loans->map(fn (Loan $l) => ApiPresenter::loan($l))->values(),
            200,
            ['count' => $loans->count()]
        );
    }

    /** Admin menyetujui: jatuh tempo = hari ini + 7, stok berkurang 1. */
    public function approve(string $id, LoanService $service): JsonResponse
    {
        $loan = ctype_digit($id) ? Loan::find($id) : null;
        if (! $loan) {
            return ApiResponse::error('LOAN_NOT_FOUND', 'Data peminjaman tidak ditemukan.', 404);
        }

        try {
            $loan = $service->approve($loan);
        } catch (LoanException $e) {
            return ApiResponse::error($e->errorCode, $e->getMessage(), 409);
        }

        return ApiResponse::success(ApiPresenter::loan($loan->load(['book', 'user']), true));
    }

    /** Admin menandai dikembalikan: stok bertambah 1. */
    public function markReturned(string $id, LoanService $service): JsonResponse
    {
        $loan = ctype_digit($id) ? Loan::find($id) : null;
        if (! $loan) {
            return ApiResponse::error('LOAN_NOT_FOUND', 'Data peminjaman tidak ditemukan.', 404);
        }

        try {
            $loan = $service->markReturned($loan);
        } catch (LoanException $e) {
            return ApiResponse::error($e->errorCode, $e->getMessage(), 409);
        }

        return ApiResponse::success(ApiPresenter::loan($loan->load(['book', 'user']), true));
    }
}
