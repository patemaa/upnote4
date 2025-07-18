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
    public bool $showFavorites = false;
    public bool $showPinned = false;

    public function toggleTrash()
    {
        $this->showArchive = false;
        $this->showFavorites = false;
        $this->showPinned = false;
        $this->showTrash = !$this->showTrash;
        $this->reset('search', 'searchResults');
    }

    public function toggleArchive()
    {
        $this->showTrash = false;
        $this->showFavorites = false;
        $this->showPinned = false;
        $this->showArchive = !$this->showArchive;
        $this->reset('search', 'searchResults');
    }

    public function toggleFavorites()
    {
        $this->showTrash = false;
        $this->showArchive = false;
        $this->showPinned = false;
        $this->showFavorites = !$this->showFavorites;
        $this->reset('search', 'searchResults');
    }
    public function togglePinned()
    {
        $this->showTrash = false;
        $this->showArchive = false;
        $this->showFavorites = false;
        $this->showPinned = !$this->showPinned;
        $this->reset('search', 'searchResults');
    }

    public function showMainView()
    {
        $this->showTrash = false;
        $this->showArchive = false;
        $this->showFavorites = false;
        $this->showPinned = false;
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

    public function unfavoriteNote(int $noteId)
    {
        Note::findOrFail($noteId)->update(['is_favorited' => false]);
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
            ->update(['is_archived' => false]);

        Category::query()
            ->where('is_archived', true)
            ->update(['is_archived' => false,]);
    }

    public function emptyFavorites()
    {
        Note::query()
            ->where('is_favorited', true)
            ->update(['is_favorited' => false]);
    }
    public function emptyPinned()
    {
        Note::query()
            ->where('is_pinned', true)
            ->update(['is_pinned' => false]);
    }


    public function pinNote(int $noteId)
    {
        Note::findOrFail($noteId)->update(['is_pinned' => true]);
    }

    public function unpinNote(int $noteId)
    {
        Note::findOrFail($noteId)->update(['is_pinned' => false]);
    }

    public function restoreAllNotes()
    {
        Note::onlyTrashed()->restore();
        Category::onlyTrashed()->restore();

        $this->dispatch('$refresh');
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
        } elseif ($this->showFavorites) {
            $data['favoritedNotes'] = Note::where('is_favorited', true)
                ->whereNull('deleted_at')
                ->where('is_archived', false)
                ->latest()
                ->get();
        } elseif ($this->showPinned) { // YENİ: Sabitlenmiş notları getiren blok
            $data['pinnedNotes'] = Note::where('is_pinned', true)
                ->whereNull('deleted_at')
                ->where('is_archived', false)
                ->latest()
                ->get();
        }

        return view('livewire.dashboard', $data);
    }
}