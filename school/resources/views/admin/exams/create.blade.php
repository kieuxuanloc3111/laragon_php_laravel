@extends('admin.layouts.app')

@push('styles')

<link rel="stylesheet"
      href="{{ asset('assets/admin/css/exams.css') }}">

@endpush

@section('title', 'Tạo đề thi')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Tạo đề thi
        </h2>

        <p class="page-subtitle">
            Tạo đề thi mới cho học sinh
        </p>

    </div>

</div>

<div class="card form-card">

    <form action="{{ route('exams.store') }}"
          method="POST">

        @csrf

        <div class="form-group">

            <label class="form-label">
                Môn học
            </label>

            <select name="subject_id"
                    class="form-select">

                @foreach($subjects as $subject)

                    <option value="{{ $subject->id }}">

                        {{ $subject->name }}

                    </option>

                @endforeach

            </select>

        </div>

        <div class="form-group">

            <label class="form-label">
                Tên đề thi
            </label>

            <input type="text"
                   name="title"
                   class="form-input"
                   placeholder="Ví dụ: Đề thi giữa kỳ Toán 12">

        </div>

        <div class="form-group">

            <label class="form-label">
                Mô tả đề thi
            </label>

            <textarea name="description"
                      class="form-textarea"
                      rows="5"></textarea>

        </div>

        <div class="form-grid">

            <div class="form-group">

                <label class="form-label">
                    Thời gian làm bài
                </label>

                <input type="number"
                       name="duration_minutes"
                       value="60"
                       class="form-input">

            </div>

            <div class="form-group">

                <label class="form-label">
                    Trạng thái
                </label>

                <select name="status"
                        class="form-select">

                    <option value="draft">
                        Bản nháp
                    </option>

                    <option value="published">
                        Xuất bản
                    </option>

                    <option value="archived">
                        Lưu trữ
                    </option>

                </select>

            </div>

        </div>

        <div class="form-actions">

            <a href="{{ route('exams.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-plus"></i>

                Tạo đề thi

            </button>

        </div>

    </form>

</div>

@endsection