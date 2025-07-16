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
    public $showArchive = false; // Arşiv kutusunun görünürlüğünü kontrol eder
    public $archivedCategories = []; // Arşivlenmiş kategorileri tutar

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
            $this->dispatch('clearEditor'); // Eğer bir kategori seçimi kaldırıldıysa editörü temizleyelim (isteğe bağlı)
        } else {
            $this->selectedCategoryId = $categoryId;
            $this->dispatch('categorySelected', categoryId: $categoryId);

            // Seçilen kategoriye ait ilk notu bul
            $firstNote = Note::where('category_id', $categoryId)->latest()->first();

            // Eğer ilk not varsa, yeni bir event ile Notes bileşenine gönder
            if ($firstNote) {
                $this->dispatch('firstNoteSelectedInCategory', noteId: $firstNote->id); // Event adını değiştirdik
            } else {
                $this->dispatch('clearEditor'); // Eğer kategoride not yoksa editörü temizleyelim (isteğe bağlı)
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