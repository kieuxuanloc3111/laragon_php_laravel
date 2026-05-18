@extends('admin.layouts.app')

@section('content')

<h1>Edit Chapter</h1>

<form
    action="{{ route('chapters.update', $chapter->id) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    <div>
        <label>Subject</label>
        <br>

        <select name="subject_id">

            @foreach($subjects as $subject)

                <option
                    value="{{ $subject->id }}"
                    {{ $chapter->subject_id == $subject->id ? 'selected' : '' }}
                >
                    {{ $subject->name }}
                </option>

            @endforeach

        </select>
    </div>

    <br>

    <div>
        <label>Name</label>
        <br>

        <input
            type="text"
            name="name"
            value="{{ $chapter->name }}"
        >
    </div>

    <br>

    <div>
        <label>Order</label>
        <br>

        <input
            type="number"
            name="order_index"
            value="{{ $chapter->order_index }}"
        >
    </div>

    <br>

    <button type="submit">
        Update
    </button>

</form>

@endsection