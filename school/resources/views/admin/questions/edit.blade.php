@extends('admin.layouts.app')

@section('content')

<h1>Edit Question</h1>

<form
    action="{{ route('questions.update', $question->id) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    <div>
        <label>Subject</label>
        <br>

        <select id="subject-select">

            @foreach($subjects as $subject)

                <option
                    value="{{ $subject->id }}"
                    {{ $question->chapter->subject_id == $subject->id ? 'selected' : '' }}
                >

                    {{ $subject->name }}

                </option>

            @endforeach

        </select>
    </div>

    <br>

    <div>
        <label>Chapter</label>
        <br>

        <select
            name="chapter_id"
            id="chapter-select"
        >

            @foreach(
                $question->chapter->subject->chapters
                as $chapter
            )

                <option
                    value="{{ $chapter->id }}"
                    {{ $question->chapter_id == $chapter->id ? 'selected' : '' }}
                >

                    {{ $chapter->name }}

                </option>

            @endforeach

        </select>
    </div>

    <br>

    <div>
        <label>Question</label>
        <br>

        <textarea
            name="content"
            rows="5"
            cols="70"
        >{{ $question->content }}</textarea>
    </div>

    <br>

    <div>
        <label>Difficulty</label>
        <br>

        <select name="difficulty">

            <option
                value="easy"
                {{ $question->difficulty == 'easy' ? 'selected' : '' }}
            >
                Easy
            </option>

            <option
                value="medium"
                {{ $question->difficulty == 'medium' ? 'selected' : '' }}
            >
                Medium
            </option>

            <option
                value="hard"
                {{ $question->difficulty == 'hard' ? 'selected' : '' }}
            >
                Hard
            </option>

        </select>
    </div>

    <br>

    <h3>Answers</h3>

    @php
        $letters = ['A', 'B', 'C', 'D'];
    @endphp

    @foreach($question->answers as $index => $answer)

        <div>

            <strong>
                {{ $letters[$index] }}.
            </strong>

            <input
                type="text"
                name="answers[]"
                value="{{ $answer->content }}"
                style="width:400px;"
            >

            <label>

                <input
                    type="radio"
                    name="correct_answer"
                    value="{{ $index }}"
                    {{ $answer->is_correct ? 'checked' : '' }}
                >

                Correct

            </label>

        </div>

        <br>

    @endforeach

    <div>
        <label>Explanation</label>
        <br>

        <textarea
            name="explanation"
            rows="4"
            cols="70"
        >{{ $question->explanation }}</textarea>
    </div>

    <br>

    <button type="submit">
        Update Question
    </button>

</form>

<script>

    const subjects = @json($subjects);

    const subjectSelect =
        document.getElementById('subject-select');

    const chapterSelect =
        document.getElementById('chapter-select');

    subjectSelect.addEventListener('change', function () {

        const subjectId = this.value;

        chapterSelect.innerHTML = '';

        const subject = subjects.find(item =>
            item.id == subjectId
        );

        if (!subject) return;

        subject.chapters.forEach(chapter => {

            chapterSelect.innerHTML += `
                <option value="${chapter.id}">
                    ${chapter.name}
                </option>
            `;

        });

    });

</script>
@endsection