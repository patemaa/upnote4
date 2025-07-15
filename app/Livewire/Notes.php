<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\Attributes\Url;

class Notes extends Component
{
    public $notes;
    public $showTrash = false;
    public $trashedNotes = [];

    #[Url(as: 'note')]
    public ?int $selectedNoteId = null;
    public ?int $selectedCategoryIdForNotes = null;
    public $categoryName = 'Tüm Notlar';

    protected $listeners = [
        'noteCreated' => 'refreshAndSelectNote',
        'noteUpdated' => 'refreshAndSelectNote',
        'noteSelected' => 'setSelectedNote',
        'clearEditor' => 'clearSelection',
        'categorySelected' => 'filterNotesByCategory',
    ];

    public function mount()
    {
        if (request()->get('category')) {
            $this->filterNotesByCategory(request()->get('category'));
        } else {
            $this->loadNotes();
        }
    }

    public function loadNotes(int $categoryId = null)
    {
        $this->notes = Note::latest()
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when(!$this->showTrash, function ($query) {
                return $query->whereNull('deleted_at');
            })
            ->get();

        $this->trashedNotes = Note::onlyTrashed()->latest()->get();
        $this->selectedCategoryIdForNotes = $categoryId;
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
            $this->loadNotes();
            $this->categoryName = 'Tüm Notlar';
        } else {
            $this->loadNotes($categoryId);
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
        $this->loadNotes($this->selectedCategoryIdForNotes);
    }

    public function toggleTrash()
    {
        $this->showTrash = !$this->showTrash;
        $this->loadNotes($this->selectedCategoryIdForNotes);
    }

    public function permanentDeleteNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->forceDelete();
        $this->loadNotes($this->selectedCategoryIdForNotes);
    }

    public function restoreNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->restore();
        $this->loadNotes();
    }

    public function render()
    {
        return view('livewire.notes');
    }
}