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

    #[Url(as: 'category')]
    public ?int $selectedCategoryId = null;

    public function mount()
    {
        $this->categories = Category::all();
        if(request()->get('category')) {
            $this->selectCategory(request()->get('category'));
        }
    }

    public function selectCategory(int $categoryId)
    {
        if ($this->selectedCategoryId === $categoryId) {
            $this->selectedCategoryId = null;
            $this->categoryName = 'Notes';
            $this->dispatch('categorySelected', categoryId: null);
        } else {
            $this->selectedCategoryId = $categoryId;
            $this->categoryName = Category::find($categoryId)->name;
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
        $this->categories = Category::all();
    }


    public function render()
    {
        return view('livewire.categories');
    }
}