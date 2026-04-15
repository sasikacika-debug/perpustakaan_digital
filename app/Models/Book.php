<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Loan;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year',
        'category_id',
        'total_stock',
        'available_stock',
        'description',
        'cover_image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getCoverUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('book_covers/' . $this->cover_image);
        }

        return 'https://via.placeholder.com/220x320.png?text=Cover+Belum+Tersedia';
    }
}
