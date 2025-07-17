<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;

class Notes extends Component
{
    public $showTrash = false;
    public $showArchive = false;
    public $trashedNotes = [];
    public $archivedNotes = [];
    public ?int $selectedNoteId = null;
    public ?int $selectedCategoryIdForNotes = null;
    public $categoryName = 'All Notes';
    public string $search = '';

    protected $listeners = [
        'noteCreated' => 'refreshAndSelectNote',
        'noteUpdated' => 'refreshAndSelectNote',
        'noteSelected' => 'setSelectedNote',
        'clearEditor' => 'clearSelection',
        'categorySelected' => 'filterNotesByCategory',
        'firstNoteSelectedInCategory' => 'handleFirstNoteSelectedInCategory',
        'searchUpdated' => 'filterNotesBySearch',
    ];

    public function mount()
    {
        if ($this->selectedCategoryIdForNotes) {
            $category = Category::find($this->selectedCategoryIdForNotes);
            $this->categoryName = $category ? $category->name : 'Kategori Bulunamadı';
        } else {
            $this->categoryName = 'All Notes';
        }
    }

    public function refreshAndSelectNote($id = null)
    {
        $this->setSelectedNote($id);
    }

    public function setSelectedNote($id)
    {
        $this->selectedNoteId = $id;
    }

    public function handleFirstNoteSelectedInCategory($noteId)
    {
        $this->selectedNoteId = $noteId;
        $this->dispatch('editNote', id: $noteId);
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
        $this->selectedNoteId = null;
        $this->dispatch('clearEditor');
        $this->reset('search');

        if ($categoryId === null) {
            $this->categoryName = 'All Notes';
        } else {
            $category = Category::find($categoryId);
            $this->categoryName = $category ? $category->name : 'Kategori Bulunamadı';
        }
    }

    public function deleteNote(int $noteId)
    {
        if ($noteId === $this->selectedNoteId) {
            $this->selectedNoteId = null;
            $this->dispatch('clearEditor');
        }
        Note::find($noteId)->delete();
    }

    public function toggleTrash()
    {
        $this->showTrash = !$this->showTrash;
        $this->showArchive = false;
    }

    public function archiveNote(int $noteId)
    {
        if ($noteId === $this->selectedNoteId) {
            $this->selectedNoteId = null;
            $this->dispatch('clearEditor');
        }
        Note::findOrFail($noteId)->update(['is_archived' => true, 'archived_at' => now()]);
    }

    public function unarchiveNote(int $noteId)
    {
        Note::findOrFail($noteId)->update(['is_archived' => false, 'archived_at' => null]);
    }

    public function permanentDeleteNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->forceDelete();
    }

    public function permanentDeleteFromArchive(int $noteId)
    {
        Note::findOrFail($noteId)->forceDelete();
    }

    public function restoreNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->restore();
    }

    public function toggleArchive()
    {
        $this->showArchive = !$this->showArchive;
        $this->showTrash = false;
    }

    public function render()
    {
        $notes = Note::latest()
            ->whereNull('deleted_at')
            ->where('is_archived', false)
            ->when($this->selectedCategoryIdForNotes, function ($query) {
                return $query->where('category_id', $this->selectedCategoryIdForNotes);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('body', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        $this->trashedNotes = Note::onlyTrashed()->latest()->get();

        $this->archivedNotes = Note::latest()
            ->where('is_archived', true)
            ->whereNull('deleted_at')
            ->when($this->selectedCategoryIdForNotes, function ($query) {
                return $query->where('category_id', $this->selectedCategoryIdForNotes);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('body', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        return view('livewire.notes', [
            'notes' => $notes,
        ]);
    }
}