<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'borrowed_at',
        'due_at',
        'returned_at',
        'fine',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function calculateFine(): int
    {
        if (!$this->returned_at || !$this->due_at) {
            return 0;
        }

        $due = \Carbon\Carbon::parse($this->due_at);
        $returned = \Carbon\Carbon::parse($this->returned_at);
        $daysLate = max(0, $returned->diffInDays($due));
        if ($daysLate > 7) {
            return ($daysLate - 7) * 1000;
        }
        return 0;
    }
}
