<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users realistis untuk Kepala
        $kepala = User::factory()->create([
            'name' => 'Kepala Perpustakaan',
            'email' => 'kepala@example.com',
            'role' => 'kepala',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $petugas = User::factory()->create([
            'name' => 'Admin Petugas',
            'email' => 'petugas@example.com',
            'role' => 'petugas',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $anggota1 = User::factory()->create([
            'name' => 'Umi Anggota',
            'email' => 'umi@example.com',
            'role' => 'anggota',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $anggota2 = User::factory()->create([
            'name' => 'Budi Anggota',
            'email' => 'budi@example.com',
            'role' => 'anggota',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $anggota3 = User::factory()->create([
            'name' => 'Sinta Anggota',
            'email' => 'sinta@example.com',
            'role' => 'anggota',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $anggota4 = User::factory()->create([
            'name' => 'Rudi Anggota',
            'email' => 'rudi@example.com',
            'role' => 'anggota',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\Models\Category::create(['name' => 'Teknologi', 'description' => 'Buku terkait teknologi informasi']);
        \App\Models\Category::create(['name' => 'Sains', 'description' => 'Buku sains dan ilmu pengetahuan']);
        \App\Models\Category::create(['name' => 'Sastra', 'description' => 'Buku fiksi dan sastra']);
        \App\Models\Category::create(['name' => 'Sejarah', 'description' => 'Buku sejarah dan biografi']);
        \App\Models\Category::create(['name' => 'Bisnis', 'description' => 'Buku bisnis dan ekonomi']);

        // Buku Teknologi
        $book1 = \App\Models\Book::create(['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'author' => 'Robert C. Martin', 'publisher' => 'Prentice Hall', 'year' => '2008', 'category_id' => 1, 'total_stock' => 10, 'available_stock' => 10, 'description' => 'Panduan untuk menulis kode yang bersih dan maintainable.']);
        $book2 = \App\Models\Book::create(['title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt & David Thomas', 'publisher' => 'Addison-Wesley', 'year' => '1999', 'category_id' => 1, 'total_stock' => 8, 'available_stock' => 8, 'description' => 'Buku klasik tentang praktik pemrograman yang baik.']);
        $book3 = \App\Models\Book::create(['title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'author' => 'Gang of Four', 'publisher' => 'Addison-Wesley', 'year' => '1994', 'category_id' => 1, 'total_stock' => 6, 'available_stock' => 6, 'description' => 'Buku fundamental tentang pola desain perangkat lunak.']);

        // Buku Sains
        $book4 = \App\Models\Book::create(['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'publisher' => 'Bantam Books', 'year' => '1988', 'category_id' => 2, 'total_stock' => 7, 'available_stock' => 7, 'description' => 'Penjelasan tentang kosmologi dan fisika modern.']);
        \App\Models\Book::create(['title' => 'The Gene: An Intimate History', 'author' => 'Siddhartha Mukherjee', 'publisher' => 'Scribner', 'year' => '2016', 'category_id' => 2, 'total_stock' => 5, 'available_stock' => 5, 'description' => 'Sejarah genetik dan biologi molekuler.']);
        \App\Models\Book::create(['title' => 'Sapiens: A Brief History of Humankind', 'author' => 'Yuval Noah Harari', 'publisher' => 'Harper', 'year' => '2014', 'category_id' => 2, 'total_stock' => 9, 'available_stock' => 9, 'description' => 'Sejarah manusia dari zaman batu hingga sekarang.']);

        // Buku Sastra
        \App\Models\Book::create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'publisher' => 'J.B. Lippincott & Co.', 'year' => '1960', 'category_id' => 3, 'total_stock' => 12, 'available_stock' => 12, 'description' => 'Klasik Amerika tentang ras dan keadilan.']);
        \App\Models\Book::create(['title' => '1984', 'author' => 'George Orwell', 'publisher' => 'Secker & Warburg', 'year' => '1949', 'category_id' => 3, 'total_stock' => 8, 'available_stock' => 8, 'description' => 'Distopia tentang totalitarianisme.']);
        \App\Models\Book::create(['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'publisher' => 'T. Egerton', 'year' => '1813', 'category_id' => 3, 'total_stock' => 6, 'available_stock' => 6, 'description' => 'Romansa klasik tentang cinta dan masyarakat.']);

        // Buku Sejarah
        \App\Models\Book::create(['title' => 'Guns, Germs, and Steel', 'author' => 'Jared Diamond', 'publisher' => 'W.W. Norton & Company', 'year' => '1997', 'category_id' => 4, 'total_stock' => 4, 'available_stock' => 4, 'description' => 'Mengapa beberapa masyarakat maju lebih cepat.']);
        \App\Models\Book::create(['title' => 'The Wright Brothers', 'author' => 'David McCullough', 'publisher' => 'Simon & Schuster', 'year' => '2015', 'category_id' => 4, 'total_stock' => 3, 'available_stock' => 3, 'description' => 'Biografi tentang pencipta pesawat terbang.']);

        // Buku Bisnis
        $book5 = \App\Models\Book::create(['title' => 'The Lean Startup', 'author' => 'Eric Ries', 'publisher' => 'Crown Business', 'year' => '2011', 'category_id' => 5, 'total_stock' => 11, 'available_stock' => 11, 'description' => 'Metode untuk membangun bisnis yang inovatif.']);
        $book6 = \App\Models\Book::create(['title' => 'Rich Dad Poor Dad', 'author' => 'Robert Kiyosaki', 'publisher' => 'Warner Books', 'year' => '1997', 'category_id' => 5, 'total_stock' => 15, 'available_stock' => 15, 'description' => 'Pelajaran tentang keuangan pribadi.']);

        // Peminjaman nyata 5 record
        \App\Models\Loan::create(['user_id' => $anggota1->id, 'book_id' => $book1->id, 'status' => 'approved', 'borrowed_at' => now()->subDays(12)->toDateString(), 'due_at' => now()->subDays(2)->toDateString(), 'returned_at' => null, 'fine' => 0]);
        \App\Models\Loan::create(['user_id' => $anggota2->id, 'book_id' => $book3->id, 'status' => 'returned', 'borrowed_at' => now()->subDays(20)->toDateString(), 'due_at' => now()->subDays(10)->toDateString(), 'returned_at' => now()->subDays(9)->toDateString(), 'fine' => 0]);
        \App\Models\Loan::create(['user_id' => $anggota3->id, 'book_id' => $book4->id, 'status' => 'pending', 'borrowed_at' => null, 'due_at' => null, 'returned_at' => null, 'fine' => 0]);
        \App\Models\Loan::create(['user_id' => $anggota4->id, 'book_id' => $book5->id, 'status' => 'return_requested', 'borrowed_at' => now()->subDays(5)->toDateString(), 'due_at' => now()->addDays(5)->toDateString(), 'returned_at' => null, 'fine' => 0]);
        \App\Models\Loan::create(['user_id' => $anggota2->id, 'book_id' => $book2->id, 'status' => 'approved', 'borrowed_at' => now()->subDays(1)->toDateString(), 'due_at' => now()->addDays(13)->toDateString(), 'returned_at' => null, 'fine' => 0]);
    }
}
