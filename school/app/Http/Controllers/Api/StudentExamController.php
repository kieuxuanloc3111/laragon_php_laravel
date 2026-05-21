<?php

namespace App\Http\Controllers\Api;
use App\Models\StudentExam;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\StudentAnswer;

use App\Models\Exam;
class StudentExamController extends Controller
{
    //
    public function index()
        {
            $exams = Exam::with('subject')
                ->where('status', 'published')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $exams
            ]);
        }
    public function start(Exam $exam)
    {
        // chỉ cho thi đề published
        if ($exam->status !== 'published') {

            return response()->json([
                'success' => false,
                'message' => 'Exam not available'
            ], 404);
        }

        $userId = auth()->id();

        // kiểm tra đang thi dở
        $existingExam = StudentExam::where(
            'user_id',
            $userId
        )
        ->where('exam_id', $exam->id)
        ->where('status', 'in_progress')
        ->first();

        // nếu có bài đang làm thì dùng lại
        if ($existingExam) {

            $studentExam = $existingExam;

        } else {

            // tạo phiên làm bài
            $studentExam = StudentExam::create([
                'user_id' => $userId,
                'exam_id' => $exam->id,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        // load câu hỏi + đáp án
        $exam->load([
            'questions.answers'
        ]);

        // ẩn đáp án đúng
        $exam->questions->each(function ($question) {

            $question->answers->makeHidden([
                'is_correct',
                'created_at',
                'updated_at'
            ]);
        });

        return response()->json([
            'success' => true,

            'student_exam_id' => $studentExam->id,

            'data' => $exam
        ]);
    }

    public function submit(
        Request $request,
        StudentExam $studentExam
    ) {
        if (
            $studentExam->user_id !== auth()->id()
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        // tránh submit lại
        if ($studentExam->status === 'submitted') {

            return response()->json([
                'success' => false,
                'message' => 'Exam already submitted'
            ], 400);
        }

        $answers = $request->answers;

        $correctCount = 0;

        foreach ($answers as $item) {

            $questionId = $item['question_id'];

            $answerId = $item['answer_id'];

            // tìm đáp án
            $answer = Answer::find($answerId);

            // lưu câu trả lời
            StudentAnswer::create([
                'student_exam_id' => $studentExam->id,
                'question_id' => $questionId,
                'answer_id' => $answerId,
            ]);

            // check đúng sai
            if ($answer && $answer->is_correct) {

                $correctCount++;
            }
        }

        // tổng số câu
        $totalQuestions =
            $studentExam
                ->exam
                ->questions
                ->count();

        // tính điểm
        $score = round(
            ($correctCount / $totalQuestions) * 10,
            2
        );

        // update kết quả
        $studentExam->update([
            'submitted_at' => now(),
            'correct_count' => $correctCount,
            'score' => $score,
            'status' => 'submitted',
        ]);

        return response()->json([
            'success' => true,

            'message' => 'Submit success',

            'data' => [
                'score' => $score,
                'correct_count' => $correctCount,
                'total_questions' => $totalQuestions,
            ]
        ]);
    }
    public function review(StudentExam $studentExam)
    {
        if (
            $studentExam->user_id !== auth()->id()
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }
        // chỉ review bài đã submit
        if ($studentExam->status !== 'submitted') {

            return response()->json([
                'success' => false,
                'message' => 'Exam not submitted yet'
            ], 400);
        }

        $studentExam->load([
            'exam.questions.answers',
            'answers'
        ]);

        $result = [];

        foreach ($studentExam->exam->questions as $question) {

            // tìm câu trả lời học sinh
            $studentAnswer = $studentExam
                ->answers
                ->where('question_id', $question->id)
                ->first();

            $result[] = [

                'question_id' => $question->id,

                'content' => $question->content,

                'explanation' => $question->explanation,

                'selected_answer_id' =>
                    $studentAnswer?->answer_id,

                'answers' => $question->answers
                    ->map(function ($answer) {

                        return [
                            'id' => $answer->id,
                            'content' => $answer->content,
                            'is_correct' => $answer->is_correct,
                        ];
                    })
            ];
        }

        return response()->json([
            'success' => true,

            'data' => [
                'score' => $studentExam->score,
                'correct_count' =>
                    $studentExam->correct_count,

                'questions' => $result
            ]
        ]);
    }    
    public function history()
    {
        $userId = auth()->id();

        $studentExams = StudentExam::with([
            'exam.subject'
        ])
        ->where('user_id', $userId)
        ->where('status', 'submitted')
        ->latest()
        ->get();

        $result = $studentExams->map(function (
            $studentExam
        ) {

            return [

                'student_exam_id' =>
                    $studentExam->id,

                'exam_title' =>
                    $studentExam->exam->title,

                'subject' =>
                    $studentExam->exam
                        ->subject
                        ->name,

                'score' =>
                    $studentExam->score,

                'correct_count' =>
                    $studentExam->correct_count,

                'submitted_at' =>
                    $studentExam->submitted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
