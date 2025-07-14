<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class Categories extends Component
{
    public $categories = [];
    public ?int $selectedCategoryId = null;

    public function mount()
    {
        $this->categories = Category::all();
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

    public function render()
    {
        return view('livewire.categories');
    }
}