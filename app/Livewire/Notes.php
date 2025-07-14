<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class Notes extends Component
{
    public $notes;
    public ?int $selectedNoteId = null;

    protected $listeners = [
        'noteCreated' => 'refreshAndSelectNote',
        'noteUpdated' => 'refreshAndSelectNote',
        'noteSelected' => 'setSelectedNote',
        'clearEditor' => 'clearSelection',
    ];

    public function mount()
    {
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $this->notes = Note::latest()->get();
    }

    public function refreshAndSelectNote($id = null)
    {
        $this->loadNotes();
        $this->selectedNoteId = $id;
    }

    public function setSelectedNote($id)
    {
        $this->selectedNoteId = $id;
    }

    public function clearSelection()
    {
        $this->selectedNoteId = null;
    }

    public function selectNote($noteId)
    {
        if ($this->selectedNoteId === $noteId) {
            $this->selectedNoteId = null;
            $this->dispatch('clearEditor');
        } else {
            $this->selectedNoteId = $noteId;
            $this->dispatch('editNote', id: $noteId);
        }
    }

    public function render()
    {
        return view('livewire.notes');
    }
}