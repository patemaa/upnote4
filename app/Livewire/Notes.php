<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\Attributes\Url;

class Notes extends Component
{
    public $showTrash = false;
    public $trashedNotes = [];

    #[Url(as: 'note')]
    public ?int $selectedNoteId = null;

    #[Url(as: 'category')]
    public ?int $selectedCategoryIdForNotes = null;
    public $categoryName = 'Tüm Notlar';

    #[Url(as: 's')]
    public string $search = '';

    protected $listeners = [
        'noteCreated' => 'refreshAndSelectNote',
        'noteUpdated' => 'refreshAndSelectNote',
        'noteSelected' => 'setSelectedNote',
        'clearEditor' => 'clearSelection',
        'categorySelected' => 'filterNotesByCategory',
    ];

    public function mount()
    {
        if ($this->selectedCategoryIdForNotes) {
            $category = Category::find($this->selectedCategoryIdForNotes);
            $this->categoryName = $category ? $category->name : 'Kategori Bulunamadı';
        } else {
            $this->categoryName = 'Tüm Notlar';
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
            $this->categoryName = 'Tüm Notlar';
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
    }

    public function permanentDeleteNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->forceDelete();
    }

    public function restoreNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->restore();
    }

    public function render()
    {
        $notes = Note::latest()
            ->when($this->selectedCategoryIdForNotes, function ($query) {
                return $query->where('category_id', $this->selectedCategoryIdForNotes);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('body', 'like', '%' . $this->search . '%');
                });
            })
            ->when(!$this->showTrash, function ($query) {
                return $query->whereNull('deleted_at');
            })
            ->get();

        $this->trashedNotes = Note::onlyTrashed()->latest()->get();

        return view('livewire.notes', [
            'notes' => $notes,
        ]);
    }
}