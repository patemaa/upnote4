<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\Attributes\Url;

class Notes extends Component
{
    public $notes;

    #[Url(as: 'note')]
    public ?int $selectedNoteId = null;
    public ?int $selectedCategoryIdForNotes = null;
    public $categoryName = 'Notes';

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
        $this->setSelectedNote($id);
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
        if ($categoryId === null) {
            $this->notes = Note::all();
            $this->categoryName = 'Notes';
        } else {
            $this->notes = Note::where('category_id', $categoryId)->get();
            $this->categoryName = Category::find($categoryId)->name;
        }
    }

    public function render()
    {
        return view('livewire.notes');
    }
}