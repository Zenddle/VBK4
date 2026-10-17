<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = ['name', 'nim', 'username', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function loans() { return $this->hasMany(Loan::class); }

    public function wishlist()
    {
        return $this->belongsToMany(Book::class, 'wishlists')->withTimestamps();
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }

    public function activeLoanCount(): int
    {
        return $this->loans()->whereIn('status', ['pending', 'approved'])->count();
    }
}