<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Note::create(['title' => 'Title 1', 'body' => 'Body 1']);
        Note::create(['title' => 'Title 2', 'body' => 'Body 2']);
        Note::create(['title' => 'Title 3', 'body' => 'Body 3']);
        Note::create(['title' => 'Title 4', 'body' => 'Body 4']);
        Note::create(['title' => 'Title 5', 'body' => 'Body 5']);
        Note::create(['title' => 'Title 6', 'body' => 'Body 6']);
    }
}
