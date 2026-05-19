@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/subjects-create.css') }}">

@endpush

@section('title', 'Chỉnh sửa môn học')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Chỉnh sửa môn học
        </h2>

        <p class="page-subtitle">
            Cập nhật thông tin môn học
        </p>

    </div>

</div>

<div class="card form-card">

    <form action="{{ route('subjects.update', $subject->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- TÊN MÔN HỌC --}}

        <div class="form-group">

            <label class="form-label">
                Tên môn học
            </label>

            <input type="text"
                   name="name"
                   id="name"
                   class="form-input"
                   placeholder="Ví dụ: Toán học"
                   value="{{ old('name', $subject->name) }}">

            @error('name')

                <p class="error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>



        {{-- BUTTON --}}

        <div class="form-actions">

            <a href="{{ route('subjects.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-floppy-disk"></i>

                Cập nhật môn học

            </button>

        </div>

    </form>

</div>

<script src="{{ asset('assets/admin/js/subjects-create.js') }}"></script>

@endsection