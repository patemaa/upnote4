<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';
    public array $searchResults = [];
    public bool $showTrash = false;
    public bool $showArchive = false;

    public function toggleTrash()
    {
        $this->showArchive = false;
        $this->showTrash = !$this->showTrash;
        $this->reset('search', 'searchResults');
    }

    public function toggleArchive()
    {
        $this->showTrash = false;
        $this->showArchive = !$this->showArchive;
        $this->reset('search', 'searchResults');
    }

    public function showMainView()
    {
        $this->showTrash = false;
        $this->showArchive = false;
    }

    public function restoreCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->restore();
    }

    public function forceDeleteCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->forceDelete();
    }

    public function unarchiveCategory(int $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_archived' => false]);
        $category->notes()->update(['is_archived' => false]);
    }

    public function restoreNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->restore();
    }

    public function forceDeleteNote(int $noteId)
    {
        Note::onlyTrashed()->findOrFail($noteId)->forceDelete();
    }

    public function unarchiveNote(int $noteId)
    {
        Note::findOrFail($noteId)->update(['is_archived' => false, 'archived_at' => null]);
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 1) {
            $categories = Category::search($this->search)->get()->map(function ($category) {
                return ['id' => $category->id, 'name' => $category->name, 'type' => 'category'];
            });

            $notes = Note::search($this->search)
                ->get()
                ->map(function ($note) {
                    return ['id' => $note->id, 'title' => $note->title, 'type' => 'note'];
                });

            $this->searchResults = $categories->concat($notes)->toArray();
        } else {
            $this->searchResults = [];
        }

        $this->dispatch('searchUpdated', search: $this->search);
    }

    public function selectSearchResult(int $id, string $type)
    {
        if ($type === 'category') {
            $this->dispatch('categorySelectedFromSearch', categoryId: $id);
        } elseif ($type === 'note') {
            $note = Note::find($id);
            if ($note) {
                $this->dispatch('noteSelectedFromSearch', noteId: $note->id, categoryId: $note->category_id);
            }
        }
        $this->searchResults = [];
        $this->search = '';
    }

    public function emptyTrash()
    {
        Note::onlyTrashed()->forceDelete();
        Category::onlyTrashed()->forceDelete();
    }

    public function emptyArchive()
    {
        Note::query()
            ->where('is_archived', true)
            ->delete();

        Category::query()
            ->where('is_archived', true)
            ->delete();
    }

    public function render()
    {
        $data = [];
        if ($this->showTrash) {
            $data['trashedCategories'] = Category::onlyTrashed()->latest()->get();
            $data['trashedNotes'] = Note::onlyTrashed()->latest()->get();
        } elseif ($this->showArchive) {
            $data['archivedCategories'] = Category::where('is_archived', true)->latest()->get();
            $data['archivedNotes'] = Note::where('is_archived', true)->latest()->get();
        }

        return view('livewire.dashboard', $data);
    }
}