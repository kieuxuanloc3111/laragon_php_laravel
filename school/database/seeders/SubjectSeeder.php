<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Toán',    'slug' => 'toan', 'color' => '#3B82F6'],
            ['name' => 'Vật Lý',  'slug' => 'ly',   'color' => '#10B981'],
            ['name' => 'Hóa Học', 'slug' => 'hoa',  'color' => '#F59E0B'],
            ['name' => 'Tiếng Anh', 'slug' => 'anh','color' => '#EF4444'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}