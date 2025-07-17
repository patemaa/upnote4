<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\Attributes\Url;

class Categories extends Component
{
    public $showCreateForm = false;
    public $newCategoryName = '';
    public $showTrash = false;
    public $showArchive = false;
    public $archivedCategories = [];

    #[Url(as: 'category')]
    public ?int $selectedCategoryId = null;

    public string $search = '';

    protected $listeners = [
        'categorySelected' => 'handleCategorySelected',
        'categorySelectedFromSearch' => 'selectCategoryAndFirstNote',
        'noteSelectedFromSearch' => 'handleCategorySelected', // YENİ OLAYI BURAYA EKLEYİN
    ];

    public function mount()
    {
        if ($this->selectedCategoryId) {
            $this->dispatch('categorySelected', categoryId: $this->selectedCategoryId);
        }
    }

    public function handleCategorySelected(?int $categoryId)
    {
        $this->selectedCategoryId = $categoryId;
    }

    public function selectCategoryAndFirstNote(int $categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->dispatch('categorySelected', categoryId: $categoryId);

        $firstNote = Note::where('category_id', $categoryId)->latest()->first();

        if ($firstNote) {
            $this->dispatch('firstNoteSelectedInCategory', noteId: $firstNote->id);
        } else {
            $this->dispatch('clearEditor');
        }
    }

    public function selectCategory(int $categoryId)
    {
        if ($this->selectedCategoryId === $categoryId) {
            $this->selectedCategoryId = null;
            $this->dispatch('categorySelected', categoryId: null);
            $this->dispatch('clearEditor');
        } else {
            $this->selectedCategoryId = $categoryId;
            $this->dispatch('categorySelected', categoryId: $categoryId);

            $firstNote = Note::where('category_id', $categoryId)->latest()->first();

            if ($firstNote) {
                $this->dispatch('firstNoteSelectedInCategory', noteId: $firstNote->id);
            } else {
                $this->dispatch('clearEditor');
            }
        }
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
    }

    public function createCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $this->newCategoryName,
        ]);

        $this->newCategoryName = '';
        $this->showCreateForm = false;
    }

    public function deleteCategory(int $categoryId)
    {
        if ($categoryId === $this->selectedCategoryId) {
            $this->selectedCategoryId = null;
            $this->dispatch('categorySelected', categoryId: null);
        }
        Category::find($categoryId)->delete();
    }

    public function toggleTrash()
    {
        $this->showTrash = !$this->showTrash;
        $this->showArchive = false;
    }

    public function archiveCategory(int $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_archived' => true]);

        $category->notes()->update([
            'is_archived' => true,
        ]);
    }

    public function unarchiveCategory(int $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_archived' => false]);

        $category->notes()->update([
            'is_archived' => false,
        ]);
    }

    public function permanentDeleteCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->forceDelete();
    }

    public function restoreCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->restore();
    }

    public function toggleArchive()
    {
        $this->showArchive = !$this->showArchive;
        $this->showTrash = false;
    }

    public function render()
    {
        $baseQuery = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest();

        $categories = (clone $baseQuery)->where('is_archived', false)->get();
        $trashedCategories = Category::onlyTrashed()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        $this->archivedCategories = (clone $baseQuery)->where('is_archived', true)->get();

        return view('livewire.categories', [
            'categories' => $categories,
            'trashedCategories' => $trashedCategories,
            'archivedCategories' => $this->archivedCategories,
        ]);
    }
}