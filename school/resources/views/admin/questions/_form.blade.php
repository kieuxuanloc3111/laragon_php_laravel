@php
    $isEdit = isset($question);

    $existingAnswers = $isEdit
        ? $question->answers->sortBy('id')->values()
        : collect();

    $selectedChapterId = old(
        'chapter_id',
        $isEdit ? $question->chapter_id : null
    );

    $selectedSubject = $selectedChapterId
        ? $subjects->first(fn ($subject) => $subject->chapters->contains('id', (int) $selectedChapterId))
        : null;

    $selectedSubjectId = $selectedSubject?->id;

    $answerValues = old(
        'answers',
        $isEdit
            ? $existingAnswers->pluck('content')->all()
            : ['', '', '', '']
    );

    $answerValues = array_pad(
        array_slice($answerValues, 0, 4),
        4,
        ''
    );

    $correctAnswer = old('correct_answer');

    if ($correctAnswer === null && $isEdit) {
        $correctAnswer = $existingAnswers->search(
            fn ($answer) => (bool) $answer->is_correct
        );

        $correctAnswer = $correctAnswer === false
            ? null
            : $correctAnswer;
    }

    $initialChapters = $selectedSubject
        ? $selectedSubject->chapters->sortBy('order_index')->values()
        : collect();
@endphp

<div class="page-header">

    <div>

        <h2 class="page-title">
            {{ $pageTitle }}
        </h2>

        <p class="page-subtitle">
            {{ $pageSubtitle }}
        </p>

    </div>

    <a href="{{ route('questions.index') }}"
       class="btn btn-secondary page-back-btn">

        <i class="fa-solid fa-arrow-left"></i>

        Quay lại

    </a>

</div>

<div class="card form-card">

    <form action="{{ $formAction }}"
          method="POST"
          class="question-form">

        @csrf

        @isset($formMethod)
            @method($formMethod)
        @endisset

        @if($errors->any())

            <div class="form-alert">

                <i class="fa-solid fa-circle-exclamation"></i>

                <span>
                    Vui lòng kiểm tra lại thông tin câu hỏi.
                </span>

            </div>

        @endif

        <div class="form-grid">

            <div class="form-group">

                <label class="form-label"
                       for="subject-select">
                    Môn học
                </label>

                <select id="subject-select"
                        class="form-select"
                        required>

                    <option value=""
                            disabled
                            hidden
                            @selected(! $selectedSubjectId)>
                        Chọn môn học
                    </option>

                    @foreach($subjects as $subject)

                        <option value="{{ $subject->id }}"
                                @selected((string) $selectedSubjectId === (string) $subject->id)>
                            {{ $subject->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group"
                 id="chapter-wrapper"
                 style="{{ $selectedSubjectId ? '' : 'display:none;' }}">

                <label class="form-label"
                       for="chapter-select">
                    Chuyên đề
                </label>

                <select name="chapter_id"
                        id="chapter-select"
                        class="form-select"
                        required>

                    <option value=""
                            disabled
                            hidden
                            @selected(! $selectedChapterId)>
                        Chọn chuyên đề
                    </option>

                    @foreach($initialChapters as $chapter)

                        <option value="{{ $chapter->id }}"
                                @selected((string) $selectedChapterId === (string) $chapter->id)>
                            {{ $chapter->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label class="form-label"
                       for="difficulty-select">
                    Độ khó
                </label>

                <select name="difficulty"
                        id="difficulty-select"
                        class="form-select">

                    <option value="easy"
                            @selected(old('difficulty', $isEdit ? $question->difficulty : 'easy') === 'easy')>
                        Dễ
                    </option>

                    <option value="medium"
                            @selected(old('difficulty', $isEdit ? $question->difficulty : 'easy') === 'medium')>
                        Trung bình
                    </option>

                    <option value="hard"
                            @selected(old('difficulty', $isEdit ? $question->difficulty : 'easy') === 'hard')>
                        Khó
                    </option>

                </select>

            </div>

        </div>

        <div class="form-group editor-block">

            <div class="editor-header">

                <label class="form-label"
                       for="question-editor">
                    Nội dung câu hỏi
                </label>

                <button type="button"
                        class="math-open-btn"
                        onclick="openMathModal('question-editor')">

                    <i class="fa-solid fa-square-root-variable"></i>

                    Công thức toán

                </button>

            </div>

            <textarea name="content"
                      id="question-editor">{{ old('content', $isEdit ? $question->content : '') }}</textarea>

        </div>

        <div class="answers-section">

            <div class="section-heading">

                <h3 class="section-title">
                    Đáp án
                </h3>

            </div>

            @php
                $letters = ['A', 'B', 'C', 'D'];
            @endphp

            @for($i = 0; $i < 4; $i++)

                <div class="answer-card">

                    <div class="answer-header">

                        <div class="answer-title">

                            <div class="answer-letter">
                                {{ $letters[$i] }}
                            </div>

                            <span>
                                Đáp án {{ $letters[$i] }}
                            </span>

                        </div>

                        <label class="correct-answer">

                            <input type="radio"
                                   name="correct_answer"
                                   value="{{ $i }}"
                                   @checked((string) $correctAnswer === (string) $i)>

                            <span>
                                Đáp án đúng
                            </span>

                        </label>

                    </div>

                    <div class="answer-toolbar">

                        <button type="button"
                                class="math-open-btn"
                                onclick="openMathModal('answer_editor_{{ $i }}')">

                            <i class="fa-solid fa-square-root-variable"></i>

                            Công thức

                        </button>

                    </div>

                    <textarea name="answers[]"
                              id="answer_editor_{{ $i }}"
                              class="answer-editor">{{ $answerValues[$i] }}</textarea>

                </div>

            @endfor

        </div>

        <div class="form-group editor-block">

            <div class="editor-header">

                <label class="form-label"
                       for="explanation-editor">
                    Giải thích đáp án
                </label>

                <button type="button"
                        class="math-open-btn"
                        onclick="openMathModal('explanation-editor')">

                    <i class="fa-solid fa-square-root-variable"></i>

                    Công thức toán

                </button>

            </div>

            <textarea name="explanation"
                      id="explanation-editor">{{ old('explanation', $isEdit ? $question->explanation : '') }}</textarea>

        </div>

        <div class="form-actions">

            <a href="{{ route('questions.index') }}"
               class="btn btn-secondary">

                Hủy

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid {{ $submitIcon }}"></i>

                {{ $submitLabel }}

            </button>

        </div>

    </form>

</div>

<div class="math-modal"
     id="mathModal">

    <div class="math-modal-content">

        <div class="math-modal-header">

            <h3>
                Nhập công thức toán
            </h3>

            <button type="button"
                    class="close-btn"
                    onclick="closeMathModal()"
                    aria-label="Đóng">

                ×

            </button>

        </div>

        <math-field id="mathField"></math-field>

        <div class="math-actions">

            <button type="button"
                    class="btn btn-secondary"
                    onclick="closeMathModal()">

                Hủy

            </button>

            <button type="button"
                    class="btn btn-primary"
                    onclick="insertMath()">

                Chèn công thức

            </button>

        </div>

    </div>

</div>

@push('scripts')

<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    window.CKEDITOR_VERSION_CHECK = false;
    window.CKEDITOR_DISABLE_VERSION_CHECK = true;

    window.questionFormConfig = {
        subjects: @json($subjects),
        selectedSubjectId: @json($selectedSubjectId),
        selectedChapterId: @json($selectedChapterId),
        uploadUrl: @json(route('questions.uploadImage', ['_token' => csrf_token()])),
    };
</script>

<script type="module">
    import 'https://unpkg.com/mathlive?module';
</script>

<script src="{{ asset('assets/admin/js/question-form.js') }}"></script>

@endpush
