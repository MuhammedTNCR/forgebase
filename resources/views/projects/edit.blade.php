@extends('layouts.tenant-panel')

@section('title', 'Edit Project')
@section('heading', 'Edit Project')
@section('subheading', 'Update project details.')

@section('content')
    <div class="panel">
        <form method="POST" action="{{ route('projects.update', ['tenant' => request()->route('tenant'), 'project' => $project]) }}" class="form">
            @csrf
            @method('PUT')
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $project->name) }}" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="nav" style="justify-content: flex-start;">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('projects.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
