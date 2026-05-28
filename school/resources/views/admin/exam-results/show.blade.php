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
            Kết quả thi của {{ $student->name }}
        </h2>

        <p class="page-subtitle">
            {{ $student->email }}
        </p>

    </div>

    <a href="{{ route('exam-results.index') }}"
       class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
        Quay lại
    </a>

</div>

<div class="card results-card">

    <div class="table-responsive">

        <table class="table attempts-table">

            <thead>
                <tr>
                    <th>Tên đề</th>
                    <th>Môn học</th>
                    <th>Kết quả</th>
                    <th>Số câu đúng</th>
                    <th>Trạng thái</th>
                    <th>Thời gian nộp</th>
                </tr>
            </thead>

            <tbody>

            @forelse($student->studentExams as $studentExam)

                <tr>
                    <td>
                        <div class="exam-title">
                            {{ $studentExam->exam?->title ?? 'Đề thi đã bị xóa' }}
                        </div>
                    </td>

                    <td>
                        <span class="text-muted">
                            {{ $studentExam->exam?->subject?->name ?? 'Chưa có môn học' }}
                        </span>
                    </td>

                    <td>
                        @if(!is_null($studentExam->score))
                            <span class="score-badge">
                                {{ number_format($studentExam->score, 2) }}
                            </span>
                        @else
                            <span class="status-badge status-muted">
                                Chưa có điểm
                            </span>
                        @endif
                    </td>

                    <td>
                        <span class="text-muted">
                            {{ $studentExam->correct_count ?? 0 }}
                            /
                            {{ $studentExam->exam?->total_questions ?? 0 }}
                        </span>
                    </td>

                    <td>
                        @php
                            $statusLabels = [
                                'submitted' => 'Đã nộp',
                                'in_progress' => 'Đang làm',
                                'timeout' => 'Hết giờ',
                            ];
                        @endphp

                        <span class="status-badge status-{{ $studentExam->status }}">
                            {{ $statusLabels[$studentExam->status] ?? $studentExam->status }}
                        </span>
                    </td>

                    <td>
                        <span class="text-muted">
                            {{
                                optional(
                                    $studentExam->submitted_at
                                )->format('d/m/Y H:i') ?? 'Chưa nộp'
                            }}
                        </span>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6"
                        class="empty-state">
                        Học sinh này chưa có lần thi nào
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
