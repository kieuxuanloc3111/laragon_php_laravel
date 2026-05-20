@extends('admin.layouts.app')

@push('styles')

<link rel="stylesheet"
      href="{{ asset('assets/admin/css/exams.css') }}">

@endpush

@section('title', 'Danh sách đề thi')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Danh sách đề thi
        </h2>

        <p class="page-subtitle">
            Quản lý đề thi trong hệ thống
        </p>

    </div>

    <a href="{{ route('exams.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus"></i>

        Tạo đề thi

    </a>

</div>

@if(session('success'))

    <div class="alert-success">

        {{ session('success') }}

    </div>

@endif

<div class="exam-grid">

    @foreach($exams as $exam)

        <div class="exam-card">

            <div class="exam-card-top">

                <div>

                    <div class="exam-subject">

                        {{ $exam->subject->name }}

                    </div>

                    <h3 class="exam-title">

                        {{ $exam->title }}

                    </h3>

                </div>

                <div class="exam-status
                    {{ $exam->status }}">

                    @if($exam->status == 'draft')

                        Bản nháp

                    @elseif($exam->status == 'published')

                        Xuất bản

                    @else

                        Lưu trữ

                    @endif

                </div>

            </div>

            <div class="exam-info">

                <div class="exam-info-item">

                    <i class="fa-regular fa-clock"></i>

                    {{ $exam->duration_minutes }} phút

                </div>

                <div class="exam-info-item">

                    <i class="fa-regular fa-file-lines"></i>

                    {{ $exam->questions_count }} câu hỏi

                </div>

            </div>

            <div class="exam-actions">

                <a href="{{ route('exams.edit', $exam->id) }}"
                   class="btn-action btn-edit">

                    <i class="fa-solid fa-pen"></i>

                </a>

                <form action="{{ route('exams.destroy', $exam->id) }}"
                      method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn-action btn-delete">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </form>

            </div>

        </div>

    @endforeach

</div>

@endsection