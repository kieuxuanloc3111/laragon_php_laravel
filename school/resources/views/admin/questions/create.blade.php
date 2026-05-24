@extends('admin.layouts.app')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/questions-create.css') }}">

@endpush

@section('title', 'Thêm câu hỏi')

@section('content')

    @include('admin.questions._form', [
        'formAction' => route('questions.store'),
        'formMethod' => null,
        'pageTitle' => 'Thêm câu hỏi',
        'pageSubtitle' => 'Tạo câu hỏi trắc nghiệm THPT',
        'submitLabel' => 'Thêm câu hỏi',
        'submitIcon' => 'fa-plus',
    ])

@endsection
