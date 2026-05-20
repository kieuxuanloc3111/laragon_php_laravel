@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/questions-create.css') }}">

@endpush

@section('title', 'Thêm câu hỏi')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Thêm câu hỏi
        </h2>

        <p class="page-subtitle">
            Tạo câu hỏi trắc nghiệm THPT
        </p>

    </div>

</div>

<div class="card form-card">

    <form action="{{ route('questions.store') }}"
          method="POST">

        @csrf

        {{-- SUBJECT --}}

        <div class="form-group">

            <label class="form-label">
                Môn học
            </label>

            <select id="subject-select"
                    class="form-select"
                    required>

                <option value=""
                        selected
                        disabled
                        hidden>

                    Chọn môn học

                </option>

                @foreach($subjects as $subject)

                    <option value="{{ $subject->id }}">

                        {{ $subject->name }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- CHAPTER --}}

        <div class="form-group"
             id="chapter-wrapper"
             style="display:none;">

            <label class="form-label">
                Chuyên đề
            </label>

            <select name="chapter_id"
                    id="chapter-select"
                    class="form-select"
                    required>

                <option value=""
                        selected
                        disabled
                        hidden>

                    Chọn chuyên đề

                </option>

            </select>

        </div>

        {{-- QUESTION --}}

        <div class="form-group">

            <div class="editor-header">

                <label class="form-label">
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
                      id="question-editor"></textarea>

        </div>

        {{-- DIFFICULTY --}}

        <div class="form-group">

            <label class="form-label">
                Độ khó
            </label>

            <select name="difficulty"
                    class="form-select">

                <option value="easy">
                    Dễ
                </option>

                <option value="medium">
                    Trung bình
                </option>

                <option value="hard">
                    Khó
                </option>

            </select>

        </div>

        {{-- ANSWERS --}}

        <div class="answers-section">

            <h3 class="section-title">
                Đáp án
            </h3>

            @php
                $letters = ['A', 'B', 'C', 'D'];
            @endphp

            @for($i = 0; $i < 4; $i++)

                <div class="answer-card">

                    <div class="answer-header">

                        <div class="answer-letter">

                            {{ $letters[$i] }}

                        </div>

                        <label class="correct-answer">

                            <input type="radio"
                                   name="correct_answer"
                                   value="{{ $i }}">

                            Đáp án đúng

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
                              class="answer-editor"></textarea>

                </div>

            @endfor

        </div>

        {{-- EXPLANATION --}}

        <div class="form-group">

            <div class="editor-header">

                <label class="form-label">
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
                      id="explanation-editor"></textarea>

        </div>

        {{-- BUTTON --}}

        <div class="form-actions">

            <a href="{{ route('questions.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-plus"></i>

                Thêm câu hỏi

            </button>

        </div>

    </form>

</div>

{{-- MATH MODAL --}}

<div class="math-modal"
     id="mathModal">

    <div class="math-modal-content">

        <div class="math-modal-header">

            <h3>
                Nhập công thức toán
            </h3>

            <button type="button"
                    class="close-btn"
                    onclick="closeMathModal()">

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

@endsection

@push('scripts')


<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    window.CKEDITOR_VERSION_CHECK = false;
    window.CKEDITOR_DISABLE_VERSION_CHECK = true;
</script>

<script type="module">

    import 'https://unpkg.com/mathlive?module';

</script>

<script>

    /*
    |--------------------------------------------------------------------------
    | SUBJECT -> CHAPTER
    |--------------------------------------------------------------------------
    */

    const subjects = @json($subjects);

    const subjectSelect =
        document.getElementById('subject-select');

    const chapterSelect =
        document.getElementById('chapter-select');

    const chapterWrapper =
        document.getElementById('chapter-wrapper');

    subjectSelect.addEventListener('change', function () {

        const subjectId = this.value;

        chapterSelect.innerHTML = `
            <option value=""
                    selected
                    disabled
                    hidden>

                Chọn chuyên đề

            </option>
        `;

        const subject = subjects.find(item =>
            item.id == subjectId
        );

        if (!subject) {

            chapterWrapper.style.display = 'none';

            return;
        }

        chapterWrapper.style.display = 'block';

        subject.chapters.forEach(chapter => {

            chapterSelect.innerHTML += `
                <option value="${chapter.id}">
                    ${chapter.name}
                </option>
            `;

        });

    });

    /*
    |--------------------------------------------------------------------------
    | CKEDITOR
    |--------------------------------------------------------------------------
    */

    let questionEditor;

    let currentEditor = null;

    window.addEventListener('load', function(){

        CKEDITOR.replace('question-editor', {

            height: 300,

            allowedContent: true,

        });

        questionEditor =
            CKEDITOR.instances['question-editor'];

        CKEDITOR.replace('explanation-editor', {

            height: 220,

            allowedContent: true,

        });

        document.querySelectorAll('.answer-editor')
            .forEach((editor, index) => {

                editor.id = 'answer_editor_' + index;

                CKEDITOR.replace(editor.id, {

                    height: 120,

                    allowedContent: true,

                });

            });

        // XÓA WARNING CKEDITOR
        setTimeout(() => {

            document
                .querySelectorAll('.cke_notifications_area')
                .forEach(el => el.remove());

        }, 1000);

    });

    /*
    |--------------------------------------------------------------------------
    | MATH MODAL
    |--------------------------------------------------------------------------
    */

    function openMathModal(editorId){

        currentEditor =
            CKEDITOR.instances[editorId];

        document
            .getElementById('mathModal')
            .classList.add('active');

    }

    function closeMathModal(){

        document
            .getElementById('mathModal')
            .classList.remove('active');

    }

    /*
    |--------------------------------------------------------------------------
    | INSERT MATH
    |--------------------------------------------------------------------------
    */

    function insertMath(){

        const formula =
            document.getElementById('mathField').value;

        if(!formula) return;

        const imageUrl =
            'https://latex.codecogs.com/png.image?\\dpi{160}'
            + encodeURIComponent(formula);

        const html =
            '<img src="' + imageUrl + '" alt="math" />';

        currentEditor.focus();

        currentEditor.insertHtml(html);

        closeMathModal();

    }

</script>

@endpush