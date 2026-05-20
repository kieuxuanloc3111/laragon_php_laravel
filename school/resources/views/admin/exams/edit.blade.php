@extends('admin.layouts.app')

@push('styles')

<link rel="stylesheet"
      href="{{ asset('assets/admin/css/exams.css') }}">

@endpush

@section('title', 'Chỉnh sửa đề thi')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Chỉnh sửa đề thi
        </h2>

        <p class="page-subtitle">
            Quản lý thông tin và câu hỏi đề thi
        </p>

    </div>

</div>

@if(session('success'))

    <div class="alert-success">

        {{ session('success') }}

    </div>

@endif

{{-- =========================
    AUTO GENERATE
========================= --}}

<div class="card auto-generate-card">

    <div class="question-header">

        <div>

            <h3 class="section-title">
                Tạo đề tự động
            </h3>

            <p class="question-subtitle">
                Random câu hỏi theo chuyên đề và độ khó
            </p>

        </div>

    </div>

    <form
        action="{{ route('exams.autoGenerate', $exam->id) }}"
        method="POST"
        autocomplete="off"
    >

        @csrf

        <div class="form-grid">

            {{-- CHAPTER --}}

            <div class="form-group">

                <label class="form-label">
                    Chuyên đề
                </label>

                <select
                    name="chapter_id"
                    class="form-select"
                    id="auto-chapter-select"
                >

                    <option value="">
                        Tất cả chuyên đề
                    </option>

                    @foreach(
                        $subjects
                            ->find($exam->subject_id)
                            ?->chapters ?? []
                        as $chapter
                    )

                        <option value="{{ $chapter->id }}">

                            {{ $chapter->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- DIFFICULTY --}}

            <div class="form-group">

                <label class="form-label">
                    Độ khó
                </label>

                <select
                    name="difficulty"
                    class="form-select"
                >

                    <option value="">
                        Tất cả
                    </option>

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

        </div>

        {{-- COUNT --}}

        <div class="form-group">

            <label class="form-label">
                Số lượng câu hỏi
            </label>

            <input
                type="number"
                name="question_count"
                class="form-input"
                min="1"
                value="10"
            >

        </div>

        <button
            type="submit"
            class="btn btn-primary"
        >

            <i class="fa-solid fa-wand-magic-sparkles"></i>

            Tạo đề tự động

        </button>

    </form>

</div>

{{-- =========================
    UPDATE EXAM
========================= --}}

<form
    action="{{ route('exams.update', $exam->id) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    <div class="card form-card">

        {{-- SUBJECT --}}

        <div class="form-group">

            <label class="form-label">
                Môn học
            </label>

            <select
                name="subject_id"
                class="form-select"
                id="subject-select"
            >

                @foreach($subjects as $subject)

                    <option
                        value="{{ $subject->id }}"

                        {{
                            $exam->subject_id ==
                            $subject->id
                            ? 'selected'
                            : ''
                        }}
                    >

                        {{ $subject->name }}

                    </option>

                @endforeach

            </select>

        </div>

        {{-- CHAPTER FILTER --}}

        <div class="form-group">

            <label class="form-label">
                Chuyên đề
            </label>

            <select
                id="chapter-filter"
                class="form-select"
            >

                <option value="">
                    Tất cả chuyên đề
                </option>

            </select>

        </div>

        {{-- TITLE --}}

        <div class="form-group">

            <label class="form-label">
                Tên đề thi
            </label>

            <input
                type="text"
                name="title"
                class="form-input"
                value="{{ $exam->title }}"
            >

        </div>

        {{-- DESCRIPTION --}}

        <div class="form-group">

            <label class="form-label">
                Mô tả
            </label>

            <textarea
                name="description"
                class="form-textarea"
                rows="5"
            >{{ $exam->description }}</textarea>

        </div>

        <div class="form-grid">

            {{-- DURATION --}}

            <div class="form-group">

                <label class="form-label">
                    Thời gian làm bài
                </label>

                <input
                    type="number"
                    name="duration_minutes"
                    class="form-input"
                    value="{{ $exam->duration_minutes }}"
                >

            </div>

            {{-- STATUS --}}

            <div class="form-group">

                <label class="form-label">
                    Trạng thái
                </label>

                <select
                    name="status"
                    class="form-select"
                >

                    <option
                        value="draft"
                        {{
                            $exam->status == 'draft'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Bản nháp
                    </option>

                    <option
                        value="published"
                        {{
                            $exam->status == 'published'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Xuất bản
                    </option>

                    <option
                        value="archived"
                        {{
                            $exam->status == 'archived'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Lưu trữ
                    </option>

                </select>

            </div>

        </div>

    </div>

    {{-- =========================
        QUESTION LIST
    ========================== --}}

    <div class="card question-card">

        <div class="question-header">

            <div>

                <h3 class="section-title">
                    Danh sách câu hỏi
                </h3>

                <p class="question-subtitle">
                    Chọn các câu hỏi muốn thêm vào đề thi
                </p>

            </div>

            <div class="question-count">

                {{ $exam->questions->count() }} câu

            </div>

        </div>

        <div class="question-list">

            @foreach($questions as $index => $question)

                <label
                    class="question-item"
                    data-subject="{{ $question->chapter->subject_id }}"
                    data-chapter="{{ $question->chapter_id }}"
                >

                    <div class="question-number">
                        {{ $index + 1 }}
                    </div>

                    <div class="question-checkbox">

                        <input
                            type="checkbox"
                            name="question_ids[]"
                            value="{{ $question->id }}"

                            {{
                                $exam->questions
                                    ->contains($question->id)
                                ? 'checked'
                                : ''
                            }}
                        >

                    </div>

                    <div class="question-content">

                        <div class="question-meta">

                            <span class="subject-badge">
                                {{ $question->chapter->subject->name }}
                            </span>

                            <span class="chapter-badge">
                                {{ $question->chapter->name }}
                            </span>

                            <span class="difficulty-badge">
                                {{ $question->difficulty }}
                            </span>

                        </div>

                        <div class="question-preview">

                            {!! $question->content !!}

                        </div>

                    </div>

                </label>

            @endforeach

        </div>

    </div>

    {{-- BUTTON --}}

    <div class="form-actions">

        <a href="{{ route('exams.index') }}"
           class="btn btn-secondary">

            Quay lại

        </a>

        <button
            type="submit"
            class="btn btn-primary"
        >

            <i class="fa-solid fa-floppy-disk"></i>

            Cập nhật đề thi

        </button>

    </div>

</form>

@endsection

@push('scripts')

<script>

    const subjects = @json(
        $subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'chapters' => $subject->chapters
            ];
        })
    );

    const subjectSelect = document.getElementById('subject-select');

    const chapterFilter = document.getElementById('chapter-filter');

    const autoChapterSelect =
    document.getElementById('auto-chapter-select');

    const questionItems = document.querySelectorAll('.question-item');

    // =========================
    // RENDER CHAPTERS
    // =========================

    function renderChapters(subjectId)
    {
        chapterFilter.innerHTML = `
            <option value="">
                Tất cả chuyên đề
            </option>
        `;

        autoChapterSelect.innerHTML = `
            <option value="">
                Tất cả chuyên đề
            </option>
        `;

        const subject = subjects.find(
            s => s.id == subjectId
        );

        if (!subject) return;

        subject.chapters.forEach(chapter => {

            const option = `
                <option value="${chapter.id}">
                    ${chapter.name}
                </option>
            `;

            chapterFilter.innerHTML += option;

            autoChapterSelect.innerHTML += option;
        });
    }

    // =========================
    // FILTER QUESTIONS
    // =========================

    function filterQuestions()
    {
        const subjectId = subjectSelect.value;

        const chapterId = chapterFilter.value;

        questionItems.forEach(item => {

            const itemSubject =
                item.dataset.subject;

            const itemChapter =
                item.dataset.chapter;

            let show = true;

            // filter subject
            if (
                subjectId &&
                itemSubject != subjectId
            ) {
                show = false;
            }

            // filter chapter
            if (
                chapterId &&
                itemChapter != chapterId
            ) {
                show = false;
            }

            item.style.display =
                show ? 'flex' : 'none';
        });
    }

    // =========================
    // EVENTS
    // =========================

    subjectSelect.addEventListener(
        'change',
        function ()
        {
            renderChapters(this.value);

            filterQuestions();
        }
    );

    chapterFilter.addEventListener(
        'change',
        filterQuestions
    );

    // =========================
    // INIT
    // =========================

    renderChapters(subjectSelect.value);

    filterQuestions();

</script>

@endpush