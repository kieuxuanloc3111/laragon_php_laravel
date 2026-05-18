<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentExam;
use App\Models\StudentAnswer;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamQuestion;
use App\Models\Answer;

class StudentExamSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $exams    = Exam::where('status', 'published')->get();

        foreach ($students as $student) {
            // Mỗi học sinh làm 2 đề đầu tiên
            foreach ($exams->take(2) as $exam) {
                $examQuestions = ExamQuestion::where('exam_id', $exam->id)
                    ->with('question.answers')
                    ->get();

                $correctCount = 0;

                $studentExam = StudentExam::create([
                    'user_id'      => $student->id,
                    'exam_id'      => $exam->id,
                    'started_at'   => now()->subMinutes(rand(30, 80)),
                    'submitted_at' => now(),
                    'score'        => null,
                    'correct_count'=> null,
                    'status'       => 'submitted',
                ]);

                foreach ($examQuestions as $eq) {
                    $answers    = $eq->question->answers;
                    $correct    = $answers->firstWhere('is_correct', true);
                    $wrong      = $answers->firstWhere('is_correct', false);

                    // 70% trả lời đúng
                    $pickCorrect = rand(1, 10) <= 7;
                    $chosen      = $pickCorrect ? $correct : $wrong;

                    if ($pickCorrect) $correctCount++;

                    StudentAnswer::create([
                        'student_exam_id' => $studentExam->id,
                        'question_id'     => $eq->question_id,
                        'answer_id'       => $chosen->id,
                    ]);
                }

                $total = $examQuestions->count();
                $score = $total > 0 ? round(($correctCount / $total) * 10, 2) : 0;

                $studentExam->update([
                    'score'         => $score,
                    'correct_count' => $correctCount,
                ]);
            }
        }
    }
}