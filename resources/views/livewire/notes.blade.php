<div x-data="{ showForm: false }"
     x-init="window.addEventListener('noteCreated', () => { showForm = false })"
     class="px-5 py-5 bg-pink-300/50 basis-4/12 rounded space-y-2 flex flex-col overflow-hidden">
    <div class="flex justify-between items-center">
        <h1>{{ $categoryName }}</h1>
        <button @click="showForm = !showForm; if(showForm) { $nextTick(() => $refs.titleInput.focus()) }"
                class="hover:text-gray-400 transition duration-300">+
        </button>
    </div>

    <form x-show="showForm" @submit.prevent="$wire.createNote()" class="flex space-x-1 mt-2" x-cloak>
        <input x-ref="titleInput" type="text" wire:model.defer="newNoteTitle" placeholder="Title"
               class="border rounded px-2 py-1 w-full text-gray-900">
        <button type="submit"
                class="bg-pink-500/80 hover:bg-pink-700/80 text-white px-3 rounded transition duration-300">+
        </button>
    </form>

    <ul class="flex-grow overflow-y-auto"
        x-data
        x-init="
            new Sortable($el, {
                animation: 150,
                ghostClass: 'bg-gray-200/50',
                handle: '.handle',
                onEnd: () => {
                    let ids = Array.from($el.children).map(child => child.getAttribute('wire:key'));
                    $wire.call('updateNoteOrder', ids);
                }
            });
        "
    >
        @forelse($notes as $noteItem)
            <li wire:key="{{ $noteItem->id }}"
                class="group py-1 px-1 bg-pink-500/50 border border-pink-500/50 hover:bg-pink-700/80 rounded mb-2 flex items-center transition duration-300
                {{ $noteItem->id == $selectedNoteId ? 'bg-pink-800/80' : '' }}">
                <div class="handle pr-1 text-white cursor-grab opacity-0 group-hover:opacity-100 transition duration-300">
                    @svg('hugeicons-drag-drop-horizontal', 'w-5 h-5')
                </div>

                <span wire:click="selectNote({{ $noteItem->id }})"
                      class="flex-grow cursor-pointer">
                    {{ Str::limit($noteItem->title ?: 'Başlıksız Not', 15) }}
                </span>
                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition duration-300">
                    <button wire:click.stop="togglePinned({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-purple-400 transition duration-300">
                        @if($noteItem->is_pinned)
                            @svg('icon-pinned', 'w-5 h-5 text-purple-400')
                        @else
                            @svg('icon-unpinned', 'w-5 h-5')
                        @endif
                    </button>
                    <button wire:click.stop="toggleFavorite({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-yellow-400 transition duration-300">
                        @if($noteItem->is_favorited)
                            @svg('icon-starred', 'size-5 text-yellow-400')
                        @else
                            @svg('icon-unstarred', 'size-5')
                        @endif
                    </button>
                    <button wire:click.stop="archiveNote({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-gray-500 transition duration-300">
                        @svg('icon-archiveSomething', 'size-5')
                    </button>
                    <button wire:click.stop="deleteNote({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-red-800 transition duration-300">
                        @svg('icon-trash', 'size-5')
                    </button>
                </div>
            </li>
        @empty
            <li class="text-gray-700 p-2">No notes to show.</li>
        @endforelse
    </ul>
</div>