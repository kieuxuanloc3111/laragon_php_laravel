@extends('admin.layouts.app')

@section('content')

<h1>Subjects</h1>

<br>

<a href="{{ route('subjects.create') }}">
    Create Subject
</a>

<br><br>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Color</th>
        <th>Action</th>
    </tr>

    @foreach($subjects as $subject)

        <tr>

            <td>{{ $subject->id }}</td>

            <td>{{ $subject->name }}</td>

            <td>{{ $subject->slug }}</td>

            <td>

                <div
                    style="
                        width: 30px;
                        height: 30px;
                        background: {{ $subject->color }};
                        border-radius: 5px;
                    "
                ></div>

            </td>

            <td>

                <a href="{{ route('subjects.edit', $subject->id) }}">
                    Edit
                </a>

                <form
                    action="{{ route('subjects.destroy', $subject->id) }}"
                    method="POST"
                    style="display:inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit">
                        Delete
                    </button>
                </form>

            </td>

        </tr>

    @endforeach

</table>

@endsection