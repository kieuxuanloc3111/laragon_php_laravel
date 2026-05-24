<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubjectQuestionBankSeeder extends Seeder
{
    private const TARGET_QUESTIONS_PER_SUBJECT = 100;

    public function run(): void
    {
        $creatorId = User::where('role', 'admin')->value('id') ?? User::value('id');

        if (! $creatorId) {
            throw new RuntimeException('SubjectQuestionBankSeeder needs at least one user to set questions.created_by.');
        }

        DB::transaction(function () use ($creatorId): void {
            $subjects = Subject::with([
                'chapters' => function ($query): void {
                    $query->orderBy('order_index')->orderBy('id');
                },
            ])->orderBy('id')->get();

            foreach ($subjects as $subject) {
                $chapters = $this->chaptersFor($subject);
                $existingTotal = Question::whereHas('chapter', function ($query) use ($subject): void {
                    $query->where('subject_id', $subject->id);
                })->count();

                for (
                    $number = $existingTotal + 1;
                    $number <= self::TARGET_QUESTIONS_PER_SUBJECT;
                    $number++
                ) {
                    $chapter = $chapters[($number - 1) % $chapters->count()];
                    $questionData = $this->buildQuestionData($subject, $number);

                    $question = Question::create([
                        'chapter_id' => $chapter->id,
                        'created_by' => $creatorId,
                        'content' => $questionData['content'],
                        'explanation' => $questionData['explanation'],
                        'difficulty' => $this->difficultyFor($number),
                        'image_url' => null,
                    ]);

                    foreach ($this->answersFor($questionData['answers'], $number) as $answer) {
                        Answer::create([
                            'question_id' => $question->id,
                            'content' => $answer['content'],
                            'is_correct' => $answer['is_correct'],
                        ]);
                    }
                }
            }
        });
    }

    private function chaptersFor(Subject $subject): Collection
    {
        if ($subject->chapters->isNotEmpty()) {
            return $subject->chapters->values();
        }

        return collect([
            Chapter::create([
                'subject_id' => $subject->id,
                'name' => 'Tong hop',
                'order_index' => 1,
            ]),
        ]);
    }

    private function buildQuestionData(Subject $subject, int $number): array
    {
        return match ($subject->slug) {
            'toan' => $this->mathQuestion($number),
            'ly' => $this->physicsQuestion($number),
            'hoa' => $this->chemistryQuestion($number),
            'anh' => $this->englishQuestion($number),
            default => $this->generalQuestion($subject, $number),
        };
    }

    private function mathQuestion(int $number): array
    {
        $a = 10 + ($number % 40);
        $b = 5 + (($number * 3) % 30);
        $correct = $a + $b;

        return [
            'content' => "Cau {$number}: Gia tri cua {$a} + {$b} la bao nhieu?",
            'explanation' => "{$a} + {$b} = {$correct}.",
            'answers' => [
                (string) $correct,
                (string) ($correct + 1),
                (string) ($correct - 1),
                (string) ($correct + 3),
            ],
        ];
    }

    private function physicsQuestion(int $number): array
    {
        $speed = 5 + ($number % 20);
        $time = 2 + ($number % 6);
        $distance = $speed * $time;

        return [
            'content' => "Cau {$number}: Mot vat di chuyen duoc {$distance} m trong {$time} s. Van toc trung binh la bao nhieu?",
            'explanation' => "Van toc trung binh v = s / t = {$distance} / {$time} = {$speed} m/s.",
            'answers' => [
                "{$speed} m/s",
                ($speed + 2) . ' m/s',
                ($speed - 1) . ' m/s',
                ($speed + 4) . ' m/s',
            ],
        ];
    }

    private function chemistryQuestion(int $number): array
    {
        $atomicNumbers = [1, 6, 7, 8, 11, 12, 13, 16, 17, 20, 26, 29];
        $atomicNumber = $atomicNumbers[($number - 1) % count($atomicNumbers)];

        return [
            'content' => "Cau {$number}: Nguyen tu co so hieu nguyen tu Z = {$atomicNumber}. So proton la bao nhieu?",
            'explanation' => "So hieu nguyen tu Z bang so proton, nen dap an la {$atomicNumber}.",
            'answers' => [
                "{$atomicNumber} proton",
                ($atomicNumber + 1) . ' proton',
                max(0, $atomicNumber - 1) . ' proton',
                ($atomicNumber + 2) . ' proton',
            ],
        ];
    }

    private function englishQuestion(int $number): array
    {
        $templates = [
            [
                'content' => 'Choose the correct answer: She ____ to school every day.',
                'explanation' => 'With she in the simple present tense, the verb takes -s or -es.',
                'answers' => ['goes', 'go', 'going', 'went'],
            ],
            [
                'content' => 'Choose the correct answer: They ____ football now.',
                'explanation' => 'An action happening now uses the present continuous tense.',
                'answers' => ['are playing', 'play', 'played', 'has played'],
            ],
            [
                'content' => 'Choose the correct answer: I ____ my homework yesterday.',
                'explanation' => 'Yesterday signals the simple past tense.',
                'answers' => ['did', 'do', 'am doing', 'have done'],
            ],
            [
                'content' => 'Choose the correct answer: He has lived here ____ 2020.',
                'explanation' => 'Use since with a starting point in time.',
                'answers' => ['since', 'for', 'from', 'during'],
            ],
            [
                'content' => 'Choose the correct answer: This is the book ____ I bought last week.',
                'explanation' => 'That can introduce a defining relative clause for a thing.',
                'answers' => ['that', 'who', 'where', 'when'],
            ],
        ];

        $template = $templates[($number - 1) % count($templates)];

        return [
            'content' => "Question {$number}: {$template['content']}",
            'explanation' => $template['explanation'],
            'answers' => $template['answers'],
        ];
    }

    private function generalQuestion(Subject $subject, int $number): array
    {
        $correct = 10 + ($number % 50);

        return [
            'content' => "Cau {$number}: Chon dap an dung cho mon {$subject->name}.",
            'explanation' => "Dap an dung cua cau {$number} la lua chon co gia tri {$correct}.",
            'answers' => [
                "Gia tri {$correct}",
                'Gia tri ' . ($correct + 1),
                'Gia tri ' . ($correct - 1),
                'Gia tri ' . ($correct + 2),
            ],
        ];
    }

    private function answersFor(array $answers, int $number): array
    {
        $answerTexts = array_values($answers);
        $correctAnswer = $answerTexts[0];
        $correctIndex = ($number - 1) % count($answerTexts);

        $answerTexts[0] = $answerTexts[$correctIndex];
        $answerTexts[$correctIndex] = $correctAnswer;

        return array_map(function (string $content, int $index) use ($correctIndex): array {
            return [
                'content' => $content,
                'is_correct' => $index === $correctIndex,
            ];
        }, $answerTexts, array_keys($answerTexts));
    }

    private function difficultyFor(int $number): string
    {
        return ['easy', 'medium', 'hard'][($number - 1) % 3];
    }
}
