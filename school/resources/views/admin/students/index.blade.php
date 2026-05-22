@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/students.css') }}">
@endpush

@section('title', 'Students')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Học sinh
        </h2>

        <p class="page-subtitle">
            Quản lý danh sách tài khoản học sinh trong hệ thống
        </p>

    </div>

</div>

<div class="student-summary">

    <div class="summary-item">
        <span>Tổng học sinh</span>
        <strong>{{ $students->total() }}</strong>
    </div>

</div>

<div class="card students-card">

    <div class="table-responsive">

        <table class="table students-table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                </tr>

            </thead>

            <tbody>

            @forelse($students as $student)

                <tr>
                    <td>
                        #{{ $student->id }}
                    </td>

                    <td>
                        <div class="student-avatar">

                            @if($student->image)

                                <img src="{{ asset('storage/' . $student->image) }}"
                                     alt="{{ $student->name }}">

                            @else

                                <span>
                                    {{
                                        strtoupper(
                                            substr($student->name, 0, 1)
                                        )
                                    }}
                                </span>

                            @endif

                        </div>
                    </td>

                    <td>
                        <div class="student-name">
                            {{ $student->name }}
                        </div>
                    </td>

                    <td>
                        <span class="text-muted">
                            {{ $student->email }}
                        </span>
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
