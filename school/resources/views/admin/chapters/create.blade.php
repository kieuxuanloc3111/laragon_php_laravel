@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/chapters-form.css') }}">

@endpush

@section('title', 'Thêm chuyên đề')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Thêm chuyên đề
        </h2>

        <p class="page-subtitle">
            Tạo chương hoặc chuyên đề mới cho môn học
        </p>

    </div>

</div>

<div class="card form-card">

    <form action="{{ route('chapters.store') }}"
          method="POST">

        @csrf

        {{-- MÔN HỌC --}}

        <div class="form-group">

            <label class="form-label">
                Môn học
            </label>

            <select name="subject_id"
                    class="form-select"
                    id="subjectSelect">

                @foreach($subjects as $subject)

                    <option
                        value="{{ $subject->id }}"
                        data-next-order="{{ $subject->chapters->max('order_index') + 1 }}">

                        {{ $subject->name }}

                    </option>

                @endforeach

            </select>

            @error('subject_id')

                <p class="error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>

        {{-- TÊN CHUYÊN ĐỀ --}}

        <div class="form-group">

            <label class="form-label">
                Tên chuyên đề
            </label>

            <input type="text"
                   name="name"
                   class="form-input"
                   placeholder="Ví dụ: Hàm số"
                   value="{{ old('name') }}">

            @error('name')

                <p class="error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>

        {{-- THỨ TỰ --}}

        <div class="form-group">

            <label class="form-label">
                Thứ tự hiển thị
            </label>

            <input type="number"
                name="order_index"
                class="form-input"
                id="orderInput"
                value="1">

            <p class="input-helper">
                Số nhỏ hơn sẽ hiển thị trước
            </p>

            @error('order_index')

                <p class="error-text">
                    {{ $message }}
                </p>

            @enderror

        </div>

        {{-- BUTTON --}}

        <div class="form-actions">

            <a href="{{ route('chapters.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-plus"></i>

                Thêm chuyên đề

            </button>

        </div>

    </form>

</div>

@endsection

@push('scripts')

    <script src="{{ asset('assets/admin/js/chapters-form.js') }}"></script>

@endpush