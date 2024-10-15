<div class="container mt-5">
    <h2>Enter Access Code</h2>
    @if ($errors->any())
        <div class="container mt-5">
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <form action="{{ route('verifyAccessCode', ['slug' => $scenario->slug]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="accessCode">Access Code</label>
            <input type="text" class="form-control" id="accessCode" name="accessCode" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
