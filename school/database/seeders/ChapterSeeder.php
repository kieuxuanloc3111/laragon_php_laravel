<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chapter;
use App\Models\Subject;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        $toanId = Subject::where('slug', 'toan')->value('id');
        $lyId   = Subject::where('slug', 'ly')->value('id');
        $hoaId  = Subject::where('slug', 'hoa')->value('id');
        $anhId  = Subject::where('slug', 'anh')->value('id');

        $chapters = [
            // Toán
            ['subject_id' => $toanId, 'name' => 'Giới hạn và liên tục',       'order_index' => 1],
            ['subject_id' => $toanId, 'name' => 'Đạo hàm',                    'order_index' => 2],
            ['subject_id' => $toanId, 'name' => 'Nguyên hàm và tích phân',    'order_index' => 3],

            // Vật Lý
            ['subject_id' => $lyId,   'name' => 'Dao động cơ',                'order_index' => 1],
            ['subject_id' => $lyId,   'name' => 'Sóng cơ và sóng âm',        'order_index' => 2],
            ['subject_id' => $lyId,   'name' => 'Điện xoay chiều',            'order_index' => 3],

            // Hóa Học
            ['subject_id' => $hoaId,  'name' => 'Este - Lipit',               'order_index' => 1],
            ['subject_id' => $hoaId,  'name' => 'Cacbohidrat',                'order_index' => 2],
            ['subject_id' => $hoaId,  'name' => 'Amin - Aminoaxit - Protein', 'order_index' => 3],

            // Tiếng Anh
            ['subject_id' => $anhId,  'name' => 'Pronunciation',              'order_index' => 1],
            ['subject_id' => $anhId,  'name' => 'Grammar',                    'order_index' => 2],
            ['subject_id' => $anhId,  'name' => 'Reading Comprehension',      'order_index' => 3],
        ];

        foreach ($chapters as $chapter) {
            Chapter::create($chapter);
        }
    }
}