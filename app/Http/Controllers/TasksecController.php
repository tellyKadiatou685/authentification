<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TasksecController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks_view.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks_view.create');
    }

    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (Auth::check()) {
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required|max:255',
            ]);
    
            // Insert the validated data into the tasks table
            Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->has('status') && $request->status === 'on' ? 1 : 0,
                'user_id' => Auth::id(), // Associe l'utilisateur actuellement connecté à la tâche
            ]);
    
            return redirect()->route('task.index')->with('success', 'Tâche enregistrée avec succès');
        }
    
        return redirect()->route('login')->with('error', 'Veuillez vous connecter avant d\'ajouter une tâche.');
    }
    

    public function edit(Task $task)
    {
        return view('tasks_view.create', compact('task'));
    }

    public function update(Request $request, int $id)
    {
        // Validation des champs
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
        
        // Récupérer la tâche avec l'ID donné
        $task = Task::find($id);
        
        // Vérifier si la tâche existe
        if (!$task) {
            return redirect()->route('task.index')->with('error', 'Tâche non trouvée.');
        }
        
        // Vérifier que l'utilisateur connecté est bien celui qui a créé la tâche (si nécessaire)
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('task.index')->with('error', 'Accès non autorisé à cette tâche.');
        }
        
        // Mettre à jour la tâche
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->has('status') ? 1 : 0,
            'user_id' => Auth::id(),  // Associe l'utilisateur connecté à la tâche
        ]);
        
        // Redirection après la mise à jour avec un message de succès
        return redirect()->route('task.index')->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index')->with('success', 'Tâche supprimée avec succès');
    }
}