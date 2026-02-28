@extends('layouts.tenant-panel')

@section('title', 'Edit Project')
@section('heading', 'Edit Project')
@section('subheading', 'Update project details.')

@section('content')
    <form method="POST" action="/projects/{{ $project->id }}" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $project->name) }}" required class="w-full rounded border border-slate-300 px-3 py-2">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Update</button>
            <a href="/projects" class="rounded border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
