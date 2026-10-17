<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use DomainException;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanService $service) {}

    /** Students grouped by loan status, sorted by NIM. */
    public function dashboard()
    {
        $students = User::where('role', 'student')->orderBy('nim')
            ->with(['loans' => fn ($q) => $q->active()->with('book')])
            ->get();

        $groups = [
            'Pending Request'  => collect(),
            'Request Approved' => collect(),
            'Overdue'          => collect(),
            'No Active Loans'  => collect(),
        ];

        foreach ($students as $s) {
            $l = $s->loans;
            $key = $l->contains(fn ($x) => $x->is_overdue) ? 'Overdue'
                : ($l->contains('status', 'pending') ? 'Pending Request'
                : ($l->contains('status', 'approved') ? 'Request Approved' : 'No Active Loans'));
            $groups[$key]->push($s);
        }

        return view('admin.dashboard', compact('groups'));
    }

    public function requests()
    {
        $loans = Loan::with(['user', 'book'])->where('status', 'pending')->oldest('requested_at')->get();
        return view('admin.loan-requests', compact('loans'));
    }

    public function history(Request $request)
    {
        $loans = Loan::with(['user', 'book'])
            ->when($request->user_id, fn ($q, $v) => $q->where('user_id', $v))
            ->when($request->book_id, fn ($q, $v) => $q->where('book_id', $v))
            ->when($request->status, function ($q, $v) {
                return $v === 'overdue'
                    ? $q->where('status', 'approved')->where('due_at', '<', now())
                    : $q->where('status', $v);
            })
            ->when($request->from, fn ($q, $v) => $q->whereDate('requested_at', '>=', $v))
            ->when($request->to, fn ($q, $v) => $q->whereDate('requested_at', '<=', $v))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.loan-history', [
            'loans' => $loans,
            'users' => User::withTrashed()->where('role', 'student')->orderBy('nim')->get(),
            'books' => Book::withTrashed()->orderBy('title')->get(),
        ]);
    }

    public function approve(Loan $loan)
    {
        return $this->run(fn () => $this->service->approve($loan), 'Loan approved.');
    }

    public function decline(Request $request, Loan $loan)
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:255']]);
        return $this->run(fn () => $this->service->decline($loan, $request->reason), 'Loan declined.');
    }

    public function markReturned(Loan $loan)
    {
        return $this->run(fn () => $this->service->markReturned($loan), 'Book marked as returned.');
    }

    private function run(callable $fn, string $success)
    {
        try {
            $fn();
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', $success);
    }
}
