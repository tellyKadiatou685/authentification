@extends('tasks_view.app')
@section('content')
<h2>@if(empty($task)) Creation @else Modification @endif d'une tache</h2>

<form action="{{ empty($task) ? route('task.store') : route('task.update',$task->id) }}" method="POST">
    @csrf
    @if(!empty($task))
    @method('PUT')
    @endif
    <div class="mb-3">
        <label for="title" class="form-label">Titre</label>
        <input type="text" class="form-control" value="{{ old('title', !empty($task) ? $task->title : '') }}" name="title"  id="title">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description">
      {{ !empty($task) ? old('description', $task->description) : '' }}
</textarea>

    </div>
    <div class="mb-3">
    <input type="checkbox" name="status" class="form-check-input" id="from-check-label" 
    @checked(!empty($task) && $task->status)>

        <label for="from-check-label" class="form-label">Termine</label>

    </div>
    <button type="submit" class="btn btn-info">@if(empty($task)) Enregistrer  @else Modifier @endif</button>

</form>
</form>

@endsection