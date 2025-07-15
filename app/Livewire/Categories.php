<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Url;

class Categories extends Component
{
    public $categories = [];
    public $showCreateForm = false;
    public $newCategoryName = '';
    public $showTrash = false;
    public $trashedCategories = [];

    #[Url(as: 'category')]
    public ?int $selectedCategoryId = null;

    public function mount()
    {
        $this->loadCategories();
        if (request()->get('category')) {
            $this->selectCategory(request()->get('category'));
        }
    }

    public function loadCategories()
    {
        $this->categories = Category::query()->latest()->when(! $this->showTrash, function ($query) {
            return $query->whereNull('deleted_at');
        })->get();

        $this->trashedCategories = Category::onlyTrashed()->latest()->get();
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
            'newCategoryName' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $this->newCategoryName,
        ]);

        $this->newCategoryName = '';
        $this->showCreateForm = false;
        $this->loadCategories();
    }

    public function deleteCategory(int $categoryId)
    {
        if ($categoryId === $this->selectedCategoryId) {
            $this->selectedCategoryId = null;
            $this->dispatch('categorySelected', categoryId: null);
        }
        Category::find($categoryId)->delete();
        $this->loadCategories();
    }

    public function toggleTrash()
    {
        $this->showTrash = !$this->showTrash;
        $this->loadCategories();
    }

    public function permanentDeleteCategory(int $categoryId)
    {
        Category::onlyTrashed()->findOrFail($categoryId)->forceDelete();
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.categories');
    }
}