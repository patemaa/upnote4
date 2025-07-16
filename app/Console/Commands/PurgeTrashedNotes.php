<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Note;
use App\Models\Category;
use Carbon\Carbon;

class PurgeTrashedNotes extends Command
{
    protected $signature = 'notes:purge-trashed';

    protected $description = '30 günden eski silinmiş notları ve kategorileri kalıcı olarak siler';

    public function handle()
    {
        $threshold = Carbon::now()->subDays(30);

        Note::onlyTrashed()
            ->where('deleted_at', '<=', $threshold)
            ->forceDelete();

        Category::onlyTrashed()
            ->where('deleted_at', '<=', $threshold)
            ->forceDelete();

        $this->info('30 günden eski çöp kutusu verileri silindi.');
    }
}