<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::all();
        return view('tasks_view.index', compact('tasks'));
    }
    public function create(){
        return view('tasks_view.create');
    }
    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
    
        // Insertion des données validées dans la table tasks
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->has('status') && $request->status === 'on' ? 1 : 0, // Si la case est cochée, status = 1, sinon = 0
            'user_id' => Auth::id(), // Associe l'utilisateur connecté à la tâche
        ]);
    
        // Redirection après la création avec un message de succès
        return redirect()->route('task.index')->with('success', 'Tâche enregistrée avec succès.');
    }
    
    public function edit(int $id){
        $task = Task::find($id);
       
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
    
    public function destroy(int $id){
        $task = Task::find($id);
        if($task){
            $task->delete();
            return redirect()->route('index')->with('success', 'Tâche supprimée avec succès.');
        }
        return redirect()->route('index')->with('error', 'Tâche non trouvée.');
 
    }
    
        
        
}
