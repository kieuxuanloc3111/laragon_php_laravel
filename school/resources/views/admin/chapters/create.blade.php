@extends('admin.layouts.app')

@section('content')

<h1>Create Chapter</h1>

<form action="{{ route('chapters.store') }}" method="POST">

    @csrf

    <div>
        <label>Subject</label>
        <br>

        <select name="subject_id">

            @foreach($subjects as $subject)

                <option value="{{ $subject->id }}">
                    {{ $subject->name }}
                </option>

            @endforeach

        </select>
    </div>

    <br>

    <div>
        <label>Name</label>
        <br>

        <input type="text" name="name">
    </div>

    <br>

    <div>
        <label>Order</label>
        <br>

        <input type="number" name="order_index" value="0">
    </div>

    <br>

    <button type="submit">
        Create
    </button>

</form>

@endsection