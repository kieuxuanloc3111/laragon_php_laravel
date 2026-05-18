<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@thpt.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $students = [
            ['name' => 'Nguyễn Văn An',   'email' => 'an@student.com'],
            ['name' => 'Trần Thị Bình',   'email' => 'binh@student.com'],
            ['name' => 'Lê Hoàng Cường',  'email' => 'cuong@student.com'],
            ['name' => 'Phạm Thị Dung',   'email' => 'dung@student.com'],
            ['name' => 'Hoàng Văn Em',    'email' => 'em@student.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name'     => $student['name'],
                'email'    => $student['email'],
                'password' => Hash::make('password'),
                'role'     => 'student',
            ]);
        }
    }
}