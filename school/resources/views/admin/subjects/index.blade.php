@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet"
          href="{{ asset('assets/admin/css/subjects.css') }}">
@endpush

@section('title', 'Subjects')

@section('content')

<div class="page-header">

    <div>

        <h2 class="page-title">
            Subjects
        </h2>

        <p class="page-subtitle">
            Quản lý môn học trong hệ thống
        </p>

    </div>

    <a href="{{ route('subjects.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus"></i>

        Create Subject

    </a>

</div>

@if(session('success'))

    <div class="alert-success">

        <i class="fa-solid fa-circle-check"></i>

        {{ session('success') }}

    </div>

@endif

<div class="card">

    <table class="table">

        <thead>

            <tr>

                <th>ID</th>
                <th>Môn học</th>
                <th>Slug</th>
                <th>Màu</th>
                <th>Action</th>

            </tr>

        </thead>

        <tbody>

        @foreach($subjects as $subject)

            <tr>

                <td>
                    #{{ $subject->id }}
                </td>

                <td>

                    <div class="subject-info">

                        <div class="subject-dot"
                             style="background: {{ $subject->color }}">
                        </div>

                        <span class="subject-name">
                            {{ $subject->name }}
                        </span>

                    </div>

                </td>

                <td>

                    <span class="badge-slug">
                        {{ $subject->slug }}
                    </span>

                </td>

                <td>

                    <div class="color-box-wrapper">

                        <div class="color-box"
                             style="background: {{ $subject->color }}">
                        </div>

                        <span class="color-code">
                            {{ $subject->color }}
                        </span>

                    </div>

                </td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('subjects.edit', $subject->id) }}"
                           class="btn-action btn-edit">

                            <i class="fa-solid fa-pen"></i>

                        </a>

                        <form action="{{ route('subjects.destroy', $subject->id) }}"
                              method="POST">

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