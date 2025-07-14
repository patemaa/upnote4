<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class Notes extends Component
{
    public $notes = [];
    public function mount()
    {
        $this->notes = Note::all();
    }
    public function render()
    {
        return view('livewire.notes');
    }
}
