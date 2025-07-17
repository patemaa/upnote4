<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';
    public array $searchResults = [];

    public function updatedSearch()
    {
        if (strlen($this->search) >= 1) {
            $categories = Category::where('name', 'like', '%' . $this->search . '%')->get()->map(function ($category) {
                return ['id' => $category->id, 'name' => $category->name, 'type' => 'category'];
            });

            $notes = Note::where('title', 'like', '%' . $this->search . '%')
                ->orWhere('body', 'like', '%' . $this->search . '%')
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
                // ÖNCEKİ İKİ OLAY YERİNE, TÜM BİLGİYİ İÇEREN BU TEK OLAYI GÖNDERİN
                $this->dispatch('noteSelectedFromSearch', noteId: $note->id, categoryId: $note->category_id);
            }
        }
        $this->searchResults = [];
        $this->search = '';
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}