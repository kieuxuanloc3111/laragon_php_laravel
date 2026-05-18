<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Chapter;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id');

        $data = [
            // --- TOÁN: Đạo hàm ---
            [
                'chapter'     => 'Đạo hàm',
                'content'     => 'Đạo hàm của hàm số f(x) = x³ - 3x² + 2 tại x = 1 là?',
                'explanation' => "f'(x) = 3x² - 6x, tại x=1: f'(1) = 3 - 6 = -3",
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => '-3', 'is_correct' => true],
                    ['content' => '3',  'is_correct' => false],
                    ['content' => '0',  'is_correct' => false],
                    ['content' => '-6', 'is_correct' => false],
                ],
            ],
            [
                'chapter'     => 'Đạo hàm',
                'content'     => 'Hàm số y = sin(2x) có đạo hàm là?',
                'explanation' => "y' = 2cos(2x)",
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => '2cos(2x)',  'is_correct' => true],
                    ['content' => 'cos(2x)',   'is_correct' => false],
                    ['content' => '-2cos(2x)', 'is_correct' => false],
                    ['content' => '2sin(2x)',  'is_correct' => false],
                ],
            ],
            [
                'chapter'     => 'Đạo hàm',
                'content'     => 'Cho f(x) = e^(2x), tìm f\'(x)?',
                'explanation' => "f'(x) = 2e^(2x)",
                'difficulty'  => 'medium',
                'answers'     => [
                    ['content' => '2e^(2x)', 'is_correct' => true],
                    ['content' => 'e^(2x)',  'is_correct' => false],
                    ['content' => '2xe^x',   'is_correct' => false],
                    ['content' => 'e^x',     'is_correct' => false],
                ],
            ],

            // --- VẬT LÝ: Dao động cơ ---
            [
                'chapter'     => 'Dao động cơ',
                'content'     => 'Chu kỳ dao động của con lắc đơn phụ thuộc vào yếu tố nào?',
                'explanation' => 'T = 2π√(l/g), phụ thuộc chiều dài dây và gia tốc trọng trường.',
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => 'Chiều dài dây và gia tốc trọng trường', 'is_correct' => true],
                    ['content' => 'Khối lượng vật và chiều dài dây',       'is_correct' => false],
                    ['content' => 'Biên độ dao động',                       'is_correct' => false],
                    ['content' => 'Khối lượng vật và biên độ',              'is_correct' => false],
                ],
            ],
            [
                'chapter'     => 'Dao động cơ',
                'content'     => 'Một vật dao động điều hòa với phương trình x = 4cos(2πt) cm. Biên độ dao động là?',
                'explanation' => 'Biên độ A = 4 cm (hệ số trước cos).',
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => '4 cm',   'is_correct' => true],
                    ['content' => '2 cm',   'is_correct' => false],
                    ['content' => '8 cm',   'is_correct' => false],
                    ['content' => '2π cm',  'is_correct' => false],
                ],
            ],

            // --- HÓA HỌC: Este - Lipit ---
            [
                'chapter'     => 'Este - Lipit',
                'content'     => 'Este được tạo thành từ axit axetic và etanol có công thức là?',
                'explanation' => 'CH₃COOH + C₂H₅OH → CH₃COOC₂H₅ + H₂O (etyl axetat)',
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => 'CH₃COOC₂H₅', 'is_correct' => true],
                    ['content' => 'C₂H₅COOCH₃', 'is_correct' => false],
                    ['content' => 'CH₃COOCH₃',  'is_correct' => false],
                    ['content' => 'HCOOC₂H₅',   'is_correct' => false],
                ],
            ],
            [
                'chapter'     => 'Este - Lipit',
                'content'     => 'Phản ứng thủy phân este trong môi trường kiềm được gọi là?',
                'explanation' => 'Phản ứng xà phòng hóa là thủy phân este trong dung dịch kiềm.',
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => 'Phản ứng xà phòng hóa', 'is_correct' => true],
                    ['content' => 'Phản ứng este hóa',      'is_correct' => false],
                    ['content' => 'Phản ứng trùng hợp',     'is_correct' => false],
                    ['content' => 'Phản ứng oxi hóa',       'is_correct' => false],
                ],
            ],

            // --- TIẾNG ANH: Grammar ---
            [
                'chapter'     => 'Grammar',
                'content'     => 'Choose the correct form: "She _____ to school every day."',
                'explanation' => 'Thì hiện tại đơn với chủ ngữ she → động từ thêm s/es.',
                'difficulty'  => 'easy',
                'answers'     => [
                    ['content' => 'goes',  'is_correct' => true],
                    ['content' => 'go',    'is_correct' => false],
                    ['content' => 'going', 'is_correct' => false],
                    ['content' => 'went',  'is_correct' => false],
                ],
            ],
            [
                'chapter'     => 'Grammar',
                'content'     => 'Which sentence uses the Present Perfect correctly?',
                'explanation' => 'Present Perfect: have/has + V3, diễn tả hành động đã xảy ra có liên quan đến hiện tại.',
                'difficulty'  => 'medium',
                'answers'     => [
                    ['content' => 'I have lived here for 5 years.',      'is_correct' => true],
                    ['content' => 'I lived here for 5 years.',           'is_correct' => false],
                    ['content' => 'I am living here for 5 years.',       'is_correct' => false],
                    ['content' => 'I was living here for 5 years.',      'is_correct' => false],
                ],
            ],
        ];

        foreach ($data as $item) {
            $chapterId = Chapter::where('name', $item['chapter'])->value('id');

            $question = Question::create([
                'chapter_id'  => $chapterId,
                'content'     => $item['content'],
                'explanation' => $item['explanation'],
                'difficulty'  => $item['difficulty'],
                'image_url'   => null,
                'created_by'  => $adminId,
            ]);

            foreach ($item['answers'] as $answer) {
                Answer::create([
                    'question_id' => $question->id,
                    'content'     => $answer['content'],
                    'is_correct'  => $answer['is_correct'],
                ]);
            }
        }
    }
}