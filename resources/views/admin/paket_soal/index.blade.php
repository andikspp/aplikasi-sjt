<a href="{{ route('create.QuestionSet') }}" class="btn btn-primary">Add Question Set</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Time Limit</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($questionSets as $set)
            <tr>
                <td>{{ $set->id }}</td>
                <td>{{ $set->name }}</td>
                <td>{{ $set->time_limit }} minutes</td>
                <td>
                    <!-- Add edit and delete buttons here if needed -->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
