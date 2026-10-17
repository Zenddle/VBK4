<?php
namespace App\Http\Controllers;

use App\Models\Book;

class WishlistController extends Controller
{
    public function toggle(Book $book)
    {
        $result = auth()->user()->wishlist()->toggle($book->id);
        $msg = count($result['attached']) ? 'Added to wishlist.' : 'Removed from wishlist.';
        return back()->with('success', $msg);
    }
}
