@extends('layouts.tenant-panel')

@section('title', 'Create Project')
@section('heading', 'Create Project')
@section('subheading', 'Add a new project to this workspace.')

@section('content')
    <div class="panel">
        <form method="POST" action="{{ route('projects.store', ['tenant' => request()->route('tenant')]) }}" class="form">
            @csrf
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="nav" style="justify-content: flex-start;">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('projects.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
