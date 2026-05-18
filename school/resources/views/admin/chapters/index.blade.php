@extends('admin.layouts.app')

@section('content')

<h1>Chapters</h1>

<br>

<a href="{{ route('chapters.create') }}">
    Create Chapter
</a>

<br><br>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Subject</th>
        <th>Name</th>
        <th>Order</th>
        <th>Action</th>
    </tr>

    @foreach($chapters as $chapter)

        <tr>

            <td>{{ $chapter->id }}</td>

            <td>
                {{ $chapter->subject->name }}
            </td>

            <td>{{ $chapter->name }}</td>

            <td>{{ $chapter->order_index }}</td>

            <td>

                <a href="{{ route('chapters.edit', $chapter->id) }}">
                    Edit
                </a>

                <form
                    action="{{ route('chapters.destroy', $chapter->id) }}"
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