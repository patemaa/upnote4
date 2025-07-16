<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Url;

class Categories extends Component
{
    public $showCreateForm = false;
    public $newCategoryName = '';
    public $showTrash = false;

    #[Url(as: 'category')]
    public ?int $selectedCategoryId = null;

    #[Url(as: 'cs')]
    public string $search = '';

    public function mount()
    {
        if ($this->selectedCategoryId) {
            $this->dispatch('categorySelected', categoryId: $this->selectedCategoryId);
        }
    }

    public function selectCategory(int $categoryId)
    {
        if ($this->selectedCategoryId === $categoryId) {
            $this->selectedCategoryId = null;
            $this->dispatch('categorySelected', categoryId: null);
        } else {
            $this->selectedCategoryId = $categoryId;
            $this->dispatch('categorySelected', categoryId: $categoryId);
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
    }

    public function permanentDeleteCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->forceDelete();
    }

    public function restoreCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->restore();
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        $trashedCategories = Category::onlyTrashed()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.categories', [
            'categories' => $categories,
            'trashedCategories' => $trashedCategories,
        ]);
    }
}