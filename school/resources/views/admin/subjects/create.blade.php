@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/subjects-create.css') }}">

@endpush

@section('title', 'Create Subject')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Create Subject
        </h2>

        <p class="page-subtitle">
            Thêm môn học mới vào hệ thống
        </p>

    </div>

</div>

<div class="card form-card">

    <form action="{{ route('subjects.store') }}"
          method="POST">

        @csrf

        {{-- NAME --}}

        <div class="form-group">

            <label class="form-label">
                Subject Name
            </label>

            <input type="text"
                   name="name"
                   id="name"
                   class="form-input"
                   placeholder="Ví dụ: Toán học"
                   value="{{ old('name') }}">

            @error('name')

                <p class="error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>



        {{-- COLOR --}}

        <div class="form-group">

            <label class="form-label">
                Subject Color
            </label>

            <div class="color-picker-wrapper">

                <input type="color"
                       name="color"
                       id="colorPicker"
                       class="color-picker"
                       value="{{ old('color', '#3B82F6') }}">

            <!-- <div class="color-preview-wrapper">

                <div class="color-preview"
                    id="colorPreview">
                </div>

            </div> -->

            </div>

            @error('color')

                <p class="error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>

        {{-- BUTTON --}}

        <div class="form-actions">

            <a href="{{ route('subjects.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-plus"></i>

                Create Subject

            </button>

        </div>

    </form>

</div>

<script src="{{ asset('assets/admin/js/subjects-create.js') }}"></script>

@endsection