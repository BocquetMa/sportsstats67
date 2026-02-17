<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Exercise;

class ExerciseSearch extends Component
{
    public $search = '';
    public $selectedExercise = null;

    public function render()
    {
        $results = [];

        if (strlen($this->search) >= 2) {
            $results = Exercise::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('targetMuscles', 'like', '%' . $this->search . '%')
                ->take(6)
                ->get();
        }

        return view('livewire.exercise-search', [
            'results' => $results
        ]);
    }

    public function selectExercise($id, $name)
    {
        $this->selectedExercise = ['id' => $id, 'name' => $name];
        $this->search = '';

        // On envoie l'ID au formulaire principal (JavaScript)
        $this->dispatch('exercise-selected', id: $id);
    }
}
