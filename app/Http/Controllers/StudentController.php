<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Book;
use App\Services\LoanService;
use DomainException;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $loans = $user->loans()->with('book')->latest()->take(5)->get();
        $overdue = $user->loans()->where('status', 'approved')->where('due_at', '<', now())->count();

        return view('student.dashboard', [
            'loans'   => $loans,
            'active'  => $user->activeLoanCount(),
            'limit'   => LoanService::LIMIT,
            'overdue' => $overdue,
        ]);
    }

    public function loans()
    {
        $loans = auth()->user()->loans()->with('book')->latest()->get();
        return view('student.loans', compact('loans'));
    }

    public function wishlist()
    {
        $books = auth()->user()->wishlist()->with('category')->get();
        return view('student.wishlist', compact('books'));
    }

    public function requestLoan(StoreLoanRequest $request, LoanService $service)
    {
        try {
            $service->request(auth()->user(), Book::findOrFail($request->book_id));
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('student.loans')->with('success', 'Loan request sent. Wait for admin approval.');
    }
}
