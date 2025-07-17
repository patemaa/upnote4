<div class="p-4">
    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search..."
           class="w-[905px] p-2 border rounded mb-4 overflow-hidden">

    @if (strlen(trim($search)) >= 1)
        <div class="absolute z-10 w-[905px] bg-white border border-gray-300 rounded shadow-md overflow-hidden pr-10">
            <ul>
                @forelse ($searchResults as $result)
                    <li wire:click="selectSearchResult({{ $result['id'] }}, '{{ $result['type'] }}')"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                        @if ($result['type'] === 'category')
                            Kategori: {{ $result['name'] }}
                        @elseif ($result['type'] === 'note')
                            Not: {{ $result['title'] }}
                        @endif
                    </li>
                @empty
                    <li class="px-4 py-2 text-gray-500">Sonuç bulunamadı.</li>
                @endforelse
            </ul>
        </div>
    @endif

    <div class="text-gray-900 dark:text-gray-100 flex min-h-[650px] space-x-2">
        <livewire:categories :search="$search"/>
        <livewire:notes />
        <livewire:editor/>
    </div>
</div>