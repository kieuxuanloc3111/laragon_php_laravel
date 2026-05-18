<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Subject;
use App\Models\Question;
use App\Models\User;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id');

        $subjects = Subject::all()->keyBy('slug');

        $exams = [
            [
                'slug'       => 'toan',
                'title'      => 'Đề thi thử Toán số 1',
                'desc'       => 'Đề ôn tập chương Đạo hàm',
                'duration'   => 90,
                'status'     => 'published',
            ],
            [
                'slug'       => 'ly',
                'title'      => 'Đề thi thử Vật Lý số 1',
                'desc'       => 'Đề ôn tập chương Dao động cơ',
                'duration'   => 50,
                'status'     => 'published',
            ],
            [
                'slug'       => 'hoa',
                'title'      => 'Đề thi thử Hóa Học số 1',
                'desc'       => 'Đề ôn tập chương Este - Lipit',
                'duration'   => 50,
                'status'     => 'published',
            ],
            [
                'slug'       => 'anh',
                'title'      => 'Đề thi thử Tiếng Anh số 1',
                'desc'       => 'Đề ôn tập Grammar',
                'duration'   => 60,
                'status'     => 'published',
            ],
        ];

        foreach ($exams as $e) {
            $questions = Question::whereHas('chapter.subject', function ($q) use ($subjects, $e) {
                $q->where('id', $subjects[$e['slug']]->id);
            })->get();

            $exam = Exam::create([
                'subject_id'       => $subjects[$e['slug']]->id,
                'title'            => $e['title'],
                'description'      => $e['desc'],
                'duration_minutes' => $e['duration'],
                'total_questions'  => $questions->count(),
                'status'           => $e['status'],
                'created_by'       => $adminId,
            ]);

            foreach ($questions as $index => $question) {
                ExamQuestion::create([
                    'exam_id'     => $exam->id,
                    'question_id' => $question->id,
                    'order_index' => $index + 1,
                ]);
            }
        }
    }
}