<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category')
            ->when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.form', [
            'book' => null,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();
        unset($data['cover']);
        $data['available_copies'] = $data['total_copies'];

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);
        return redirect()->route('admin.books.index')->with('success', 'Book added.');
    }

    public function show(Book $book)
    {
        $book->load('category');
        $loans = $book->loans()->with('user')->latest()->take(20)->get();
        return view('admin.books.show', compact('book', 'loans'));
    }

    public function edit(Book $book)
    {
        return view('admin.books.form', [
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();
        unset($data['cover']);

        // keep availability consistent when total copies change
        $diff = $data['total_copies'] - $book->total_copies;
        $data['available_copies'] = max(0, $book->available_copies + $diff);

        if ($request->hasFile('cover')) {
            if ($book->cover_path) Storage::disk('public')->delete($book->cover_path);
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);
        return redirect()->route('admin.books.index')->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        $book->delete(); // soft delete
        return back()->with('success', 'Book deleted.');
    }
}
