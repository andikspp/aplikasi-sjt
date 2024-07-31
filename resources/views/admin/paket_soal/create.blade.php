<form action="{{ route('store.QuestionSet') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Set Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="time_limit" class="form-label">Time Limit (minutes)</label>
        <input type="number" class="form-control" id="time_limit" name="time_limit" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
