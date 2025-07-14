<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class Editor extends Component
{
    public ?Note $note = null;
    public string $title = '';
    public string $body = '';

    protected $rules = [
        'title' => 'required_without:body',
        'body' => 'required_without:title',
    ];

    protected $listeners = ['editNote' => 'loadNote'];

    public function mount()
    {
        $this->note = new Note();
    }

    public function loadNote($id)
    {
        $this->note = Note::find($id);
        $this->title = $this->note->title;
        $this->body = $this->note->body;
    }

    public function newNote()
    {
        $this->reset(['note', 'title', 'body']);
        $this->note = new Note();
    }

    public function updated($property)
    {
        if (!in_array($property, ['title', 'body'])) {
            return;
        }

        if (!$this->note->exists) {
            $this->note->title = $this->title ?: 'Başlıksız Not';
            $this->note->body = $this->body;
            $this->note->category_id = 1;
            $this->note->save();

            $this->dispatch('note-created', id: $this->note->id);
        } else {
            $this->note->update([
                'title' => $this->title ?: 'Başlıksız Not',
                'body' => $this->body,
            ]);
        }

        $this->dispatch('note-saved');
    }

    public function render()
    {
        return view('livewire.editor');
    }
}