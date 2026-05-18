@extends('admin.layouts.app')

@section('content')

<h1>Edit Subject</h1>

<form
    action="{{ route('subjects.update', $subject->id) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    <div>
        <label>Name</label>
        <br>

        <input
            type="text"
            name="name"
            value="{{ $subject->name }}"
        >
    </div>

    <br>

    <div>
        <label>Slug</label>
        <br>

        <input
            type="text"
            name="slug"
            value="{{ $subject->slug }}"
        >
    </div>

    <br>

    <div>
        <label>Color</label>
        <br>

        <select name="color">

            <option
                value="#3B82F6"
                {{ $subject->color == '#3B82F6' ? 'selected' : '' }}
            >
                Blue
            </option>

            <option
                value="#10B981"
                {{ $subject->color == '#10B981' ? 'selected' : '' }}
            >
                Green
            </option>

            <option
                value="#F59E0B"
                {{ $subject->color == '#F59E0B' ? 'selected' : '' }}
            >
                Orange
            </option>

            <option
                value="#EF4444"
                {{ $subject->color == '#EF4444' ? 'selected' : '' }}
            >
                Red
            </option>

        </select>
    </div>

    <br>

    <button type="submit">
        Update
    </button>

</form>

@endsection