@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/exam-results.css') }}">
@endpush

@section('title', 'Kết quả thi')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Kết quả thi
        </h2>

        <p class="page-subtitle">
            Chọn học sinh để xem các lần thi và kết quả chi tiết
        </p>

    </div>

</div>

<div class="card results-card">

    <div class="table-responsive">

        <table class="table results-table">

            <thead>
                <tr>
                    <th>Học sinh</th>
                    <th>Email</th>
                    <th>Số lần thi</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

            @forelse($students as $student)

                <tr>
                    <td>
                        <a href="{{ route('exam-results.show', $student->id) }}"
                           class="student-link">
                            {{ $student->name }}
                        </a>
                    </td>

                    <td>
                        <span class="text-muted">
                            {{ $student->email }}
                        </span>
                    </td>

                    <td>
                        <span class="attempt-count">
                            {{ $student->student_exams_count }}
                        </span>
                    </td>

                    <td class="table-action">
                        <a href="{{ route('exam-results.show', $student->id) }}"
                           class="btn-view">
                            <i class="fa-solid fa-eye"></i>
                            Xem kết quả
                        </a>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4"
                        class="empty-state">
                        Chưa có học sinh nào trong hệ thống
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if($students->hasPages())

        <div class="pagination-wrapper">
            {{ $students->links() }}
        </div>

    @endif

</div>

@endsection
