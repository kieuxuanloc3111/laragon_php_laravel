@extends('admin.layouts.app')

@section('content')

<h1>Create Subject</h1>

<form action="{{ route('subjects.store') }}" method="POST">

    @csrf

    <div>
        <label>Name</label>
        <br>

        <input type="text" name="name">
    </div>

    <br>

    <div>
        <label>Slug</label>
        <br>

        <input type="text" name="slug">
    </div>

    <br>

    <div>
        <label>Color</label>
        <br>

        <select name="color">

            <option value="#3B82F6">
                Blue
            </option>

            <option value="#10B981">
                Green
            </option>

            <option value="#F59E0B">
                Orange
            </option>

            <option value="#EF4444">
                Red
            </option>

            <option value="#8B5CF6">
                Purple
            </option>

        </select>
    </div>

    <br>

    <button type="submit">
        Create
    </button>

</form>

@endsection