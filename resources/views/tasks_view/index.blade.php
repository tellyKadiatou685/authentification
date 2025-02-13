@extends('tasks_view.app')

@section('content')
<div class="container mt-5">
    <!-- Information utilisateur -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="User">
            <span class="h5 mb-0">{{ auth()->user()->name }}</span>
        </div>
        <a href="{{ route('logout') }}" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Se déconnecter
        </a>
    </div>

    <!-- Titre -->
    <h1 class="text-center mb-4 text-primary">Liste des Tâches</h1>

    <!-- Ajouter une tâche -->
    <a href="{{ route('task.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Ajouter une tâche
    </a>

    <!-- Message de succès -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Table des tâches -->
    <table class="table table-bordered table-striped shadow-sm">
        <thead class="thead-dark">
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>
                    @if ($task->status == 1)
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Terminé</span>
                    @else
                    <span class="badge text-bg-warning"><i class="bi bi-hourglass-split"></i> En cours</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('task.edit', $task->id) }}" class="btn btn-sm btn-info">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <form action="{{ route('task.destroy', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous supprimer cette tâche ?')">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Script pour disparition du message de succès -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = "opacity 0.5s ease";
                successMessage.style.opacity = 0;

                // Supprimer le message du DOM après la transition
                setTimeout(() => successMessage.remove(), 500);
            }, 5000); // 5000ms = 5 secondes avant la disparition
        }
    });
</script>

@endsection
