@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create New Scenario</h1>

        <form action="{{ route('scenarios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                    rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="attachment">Video of afbeelding</label>
                <input type="file" class="form-control-file @error('attachment') is-invalid @enderror" id="attachment"
                    name="attachment">
                @error('attachment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1"
                        {{ old('is_public') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_public">Make this scenario public</label>
                </div>
            </div>

            <div class="form-group" id="access_code_group" style="{{ old('is_public') ? 'display:none;' : '' }}">
                <label for="access_code">Access Code (for private scenarios)</label>
                <input type="text" class="form-control @error('access_code') is-invalid @enderror" id="access_code"
                    name="access_code" value="{{ old('access_code') }}">
                @error('access_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Create Scenario</button>
            <a href="{{ route('scenarios.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    @push('styles')
        <style>
            video {
                max-width: 600px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.getElementById('is_public').addEventListener('change', function() {
                var accessCodeGroup = document.getElementById('access_code_group');
                accessCodeGroup.style.display = this.checked ? 'none' : 'block';
            });
        </script>
    @endpush
@endsection
