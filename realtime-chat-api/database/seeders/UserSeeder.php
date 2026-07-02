<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed danh sách user demo. Mọi user dùng chung mật khẩu: 123456
     * Avatar lấy từ thư mục public/storage/image (đã có sẵn ảnh).
     */
    public function run(): void
    {
        // Các ảnh có sẵn trong storage/app/public/image (tên không dấu cách).
        $images = [
            'image/Doraemon1.jpg',
            'image/images.jpg',
            'image/57_9bb613a4cf7e4f579f2ced4c568b7bc3_master.jpg',
            'image/60_3fee3bd175394ba38c1943690b214914_large.jpg',
            'image/ninja_rantaro_-_tap_63_18ec83d3738e44bf8dfadfd6e1668055_1024x1024.jpg',
        ];

        $users = [
            ['name' => 'An Nguyễn',   'email' => 'an@example.com'],
            ['name' => 'Bình Trần',   'email' => 'binh@example.com'],
            ['name' => 'Chi Lê',      'email' => 'chi@example.com'],
            ['name' => 'Dũng Phạm',   'email' => 'dung@example.com'],
            ['name' => 'Hoa Võ',      'email' => 'hoa@example.com'],
            ['name' => 'Khánh Đỗ',    'email' => 'khanh@example.com'],
            ['name' => 'Lan Hồ',      'email' => 'lan@example.com'],
            ['name' => 'Minh Bùi',    'email' => 'minh@example.com'],
        ];

        foreach ($users as $i => $data) {

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('123456'),
                    'avatar' => $images[$i % count($images)],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
