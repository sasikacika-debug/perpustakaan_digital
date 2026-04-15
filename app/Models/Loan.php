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
        'rejection_reason',
        'borrowed_at',
        'due_at',
        'returned_at',
        'fine',
        'fine_status',
        'condition',
        'requested_return_date',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'requested_return_date' => 'date',
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
        if (!$this->requested_return_date || !$this->due_at || !$this->condition) {
            return 0;
        }

        $due = \Carbon\Carbon::parse($this->due_at)->startOfDay();
        $returned = \Carbon\Carbon::parse($this->requested_return_date)->startOfDay();

        if ($returned->lessThanOrEqualTo($due)) {
            $daysLate = 0;
        } else {
            $daysLate = $due->diffInDays($returned);
        }

        if ($this->condition === 'hilang') {
            return 100000;
        } elseif ($this->condition === 'rusak') {
            return 30000;
        } elseif ($this->condition === 'baik') {
            if ($daysLate > 0) {
                return $daysLate * 3000;
            }
        }

        return 0;
    }
}
