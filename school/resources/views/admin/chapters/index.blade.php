@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/chapters.css') }}">

@endpush

@section('title', 'Chuyên đề')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Chuyên đề
        </h2>

        <p class="page-subtitle">
            Quản lý chương và chuyên đề theo từng môn học
        </p>

    </div>

    <a href="{{ route('chapters.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus"></i>

        Thêm chuyên đề

    </a>

</div>

@if(session('success'))

    <div class="alert-success">

        <i class="fa-solid fa-circle-check"></i>

        {{ session('success') }}

    </div>

@endif

@foreach($subjects as $subject)

    <div class="subject-section">

        {{-- SUBJECT HEADER --}}

        <div class="subject-header">

            <div class="subject-left">

                <div class="subject-dot"
                     style="background: {{ $subject->color }}">
                </div>

                <h3>
                    {{ $subject->name }}
                </h3>

            </div>

            <span class="chapter-count">

                {{ $subject->chapters->count() }}
                chuyên đề

            </span>

        </div>

        {{-- CHAPTERS --}}

        <div class="card">

            @if($subject->chapters->count())

                <table class="table">

                    <thead>

                        <tr>

                            <th>Thứ tự</th>
                            <th>Tên chuyên đề</th>
                            <th>Thao tác</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($subject->chapters as $chapter)

                        <tr>

                            <td>

                                <span class="order-badge">
                                    {{ $chapter->order_index }}
                                </span>

                            </td>

                            <td>

                                <div class="chapter-name">

                                    <i class="fa-solid fa-book-open"></i>

                                    {{ $chapter->name }}

                                </div>

                            </td>

                            <td>

                                <div class="action-buttons">

                                    <a href="{{ route('chapters.edit', $chapter->id) }}"
                                       class="btn-action btn-edit">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>

                                    <form action="{{ route('chapters.destroy', $chapter->id) }}"
                                          method="POST"
                                          class="delete-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn-action btn-delete delete-btn">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-chapter">

                    Chưa có chuyên đề nào

                </div>

            @endif

        </div>

    </div>

@endforeach

@push('scripts')

    <script src="{{ asset('assets/admin/js/subjects.js') }}"></script>

@endpush

@endsection