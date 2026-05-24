@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/questions-create.css') }}">

@endpush

@section('title', 'Cập nhật câu hỏi')

@section('content')

    @include('admin.questions._form', [
        'question' => $question,
        'formAction' => route('questions.update', $question->id),
        'formMethod' => 'PUT',
        'pageTitle' => 'Cập nhật câu hỏi',
        'pageSubtitle' => 'Chỉnh sửa nội dung, đáp án và giải thích',
        'submitLabel' => 'Cập nhật câu hỏi',
        'submitIcon' => 'fa-floppy-disk',
    ])

@endsection
