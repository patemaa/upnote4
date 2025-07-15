<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;

class Editor extends Component
{
    public ?Note $note = null;
    public string $title = '';
    public string $body = '';
    public $categories = [];
    public $showCategoryDropdown = false;
    public $noteCategoryId = null;

    protected $rules = [
        'title' => 'required_without:body',
        'body' => 'required_without:title',
    ];

    protected $listeners = [
        'editNote' => 'loadNote',
        'clearEditor' => 'newNote'
    ];

    public function mount()
    {
        if (request()->has('note')) {
            $this->loadNote(request()->get('note'));
        }
        $this->categories = Category::all();
    }

    public function loadNote($id)
    {
        if ($id === null || ($this->note && $this->note->id == $id)) {
            $this->newNote();
            return;
        }

        $this->note = Note::find($id);
        if ($this->note) {
            $this->title = $this->note->title;
            $this->body = $this->note->body;
            $this->noteCategoryId = $this->note->category_id;
            $this->dispatch('noteSelected', id: $this->note->id);
        } else {
            $this->newNote();
        }
    }

    public function newNote()
    {
        $this->reset(['note', 'title', 'body', 'noteCategoryId']);
        $this->note = new Note();
        $this->dispatch('noteSelected', id: null);
    }

    public function updated($property)
    {
        if (!in_array($property, ['title', 'body'])) {
            return;
        }

        if (empty($this->title) && empty($this->body)) {
            if ($this->note && !$this->note->exists) {
                $this->newNote();
            }
            return;
        }

        if (!$this->note->exists) {
            $this->note->title = $this->title ?: 'Başlıksız Not';
            $this->note->body = $this->body;
            $this->note->category_id = 1;
            $this->note->save();

            $this->dispatch('noteCreated', id: $this->note->id);
            $this->dispatch('noteSelected', id: $this->note->id);


        } else {
            $this->note->update([
                'title' => $this->title ?: 'Başlıksız Not',
                'body' => $this->body,
            ]);
            $this->dispatch('noteUpdated', id: $this->note->id);
            $this->dispatch('noteSelected', id: $this->note->id);
        }
    }

    public function toggleCategoryDropdown()
    {
        $this->showCategoryDropdown = !$this->showCategoryDropdown;
    }

    public function assignCategory($categoryId)
    {
        if ($this->note && $this->note->exists) {
            $this->note->category_id = $categoryId;
            $this->note->save();

            $this->noteCategoryId = $categoryId;
            $this->showCategoryDropdown = false;
        }
    }

    public function render()
    {
        return view('livewire.editor');
    }
}