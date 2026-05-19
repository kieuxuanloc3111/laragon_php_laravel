@extends('admin.layouts.app')
@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/questions.css') }}">

@endpush
@section('title', 'Danh sách câu hỏi')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Danh sách câu hỏi
        </h2>

        <p class="page-subtitle">
            Quản lý ngân hàng câu hỏi
        </p>

    </div>

    <a href="{{ route('questions.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus"></i>

        Thêm câu hỏi

    </a>

</div>

@if(session('success'))

    <div class="alert-success">

        {{ session('success') }}

    </div>

@endif

<div class="card">

    <table class="table">

        <thead>

            <tr>

                <th>Môn</th>

                <th>Chuyên đề</th>

                <th>Độ khó</th>

                <th>Câu hỏi</th>

                <th>Đáp án</th>

                <th>Giải thích</th>

                <th>Thao tác</th>

            </tr>

        </thead>

        <tbody>

        @foreach($questions as $question)

            <tr>

                <td>

                    {{ $question->chapter->subject->name }}

                </td>

                <td>

                    {{ $question->chapter->name }}

                </td>

                <td>

                    <span class="difficulty-badge">

                        {{ $question->difficulty }}

                    </span>

                </td>

                <td class="question-content">

                    {!! $question->content !!}

                </td>

                <td class="answers-column">

                    @php
                        $letters = ['A', 'B', 'C', 'D'];
                    @endphp

                    @foreach(
                        $question->answers
                        as $index => $answer
                    )

                        <div class="answer-item">

                            <strong>
                                {{ $letters[$index] }}.
                            </strong>

                            {!! $answer->content !!}

                            @if($answer->is_correct)

                                <span class="correct-answer">

                                    ✅

                                </span>

                            @endif

                        </div>

                    @endforeach

                </td>

                <td class="explanation-column">

                    {!! $question->explanation !!}

                </td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('questions.edit', $question->id) }}"
                           class="btn-action btn-edit">

                            <i class="fa-solid fa-pen"></i>

                        </a>

                        <form
                            action="{{ route('questions.destroy', $question->id) }}"
                            method="POST"
                            class="delete-form">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn-action btn-delete">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endsection