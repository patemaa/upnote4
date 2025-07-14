<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class Notes extends Component
{
    public $notes;
    public ?int $selectedNoteId = null;
    public ?int $selectedCategoryIdForNotes = null;

    protected $listeners = [
        'noteCreated' => 'refreshAndSelectNote',
        'noteUpdated' => 'refreshAndSelectNote',
        'noteSelected' => 'setSelectedNote',
        'clearEditor' => 'clearSelection',
        'categorySelected' => 'filterNotesByCategory',
    ];

    public function mount()
    {
        $this->loadNotes();
    }

    public function loadNotes(int $categoryId = null)
    {
        $this->notes = Note::latest()
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->get();
    }

    public function refreshAndSelectNote($id = null)
    {
        $this->loadNotes($this->selectedCategoryIdForNotes);
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

    public function filterNotesByCategory(?int $categoryId)
    {
        $this->selectedCategoryIdForNotes = $categoryId;
        $this->loadNotes($categoryId);
        $this->dispatch('clearEditor');
    }

    public function render()
    {
        return view('livewire.notes');
    }
}