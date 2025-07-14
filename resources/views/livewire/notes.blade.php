<div class="px-5 py-5 bg-emerald-500/50 basis-4/12 rounded">
    <ul>
        @foreach($notes as $noteItem)
            <li wire:key="{{ $noteItem->id }}"
                wire:click="selectNote({{ $noteItem->id }})"
                class="py-1 px-1 bg-emerald-500/50 border border-emerald-500 hover:bg-emerald-500 cursor-pointer rounded mb-2
                {{ $noteItem->id == $selectedNoteId ? 'bg-emerald-700' : '' }}">
                {{ $noteItem->title ?: 'Başlıksız Not' }}
            </li>
        @endforeach
    </ul>
</div>