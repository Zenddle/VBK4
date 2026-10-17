<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'description', 'year',
        'cover_path', 'total_copies', 'available_copies',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function loans() { return $this->hasMany(Loan::class); }

    public function getIsAvailableAttribute(): bool { return $this->available_copies > 0; }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_path ? Storage::disk('public')->url($this->cover_path) : null;
    }
}
