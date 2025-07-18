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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </div>

                <span wire:click="selectNote({{ $noteItem->id }})"
                      class="flex-grow cursor-pointer">
                    {{ Str::limit($noteItem->title ?: 'Başlıksız Not', 15) }}
                </span>
                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition duration-300">
                    <button wire:click.stop="togglePinned({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-purple-400 transition duration-300">
                        @if($noteItem->is_pinned)
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-pin text-purple-400">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15.113 3.21l.094 .083l5.5 5.5a1 1 0 0 1 -1.175 1.59l-3.172 3.171l-1.424 3.797a1 1 0 0 1 -.158 .277l-.07 .08l-1.5 1.5a1 1 0 0 1 -1.32 .082l-.095 -.083l-2.793 -2.792l-3.793 3.792a1 1 0 0 1 -1.497 -1.32l.083 -.094l3.792 -3.793l-2.792 -2.793a1 1 0 0 1 -.083 -1.32l.083 -.094l1.5 -1.5a1 1 0 0 1 .258 -.187l.098 -.042l3.796 -1.425l3.171 -3.17a1 1 0 0 1 1.497 -1.26z"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pin">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4"/>
                                <path d="M9 15l-4.5 4.5"/>
                                <path d="M14.5 4l5.5 5.5"/>
                            </svg>
                        @endif
                    </button>
                    <button wire:click.stop="toggleFavorite({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-yellow-400 transition duration-300">
                        @if($noteItem->is_favorited)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-5 text-yellow-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.11a.563.563 0 0 0 .475.346l5.518.441c.5.04.702.663.32 1.012l-4.204 3.601a.563.563 0 0 0-.182.558l1.285 5.384a.562.562 0 0 1-.84.611l-4.725-2.886a.562.562 0 0 0-.586 0l-4.725 2.886a.562.562 0 0 1-.84-.61l1.285-5.385a.563.563 0 0 0-.182-.558L2.553 10.41a.562.562 0 0 1 .32-1.011l5.518-.441a.563.563 0 0 0 .475-.346l2.125-5.11Z"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.32 1.011l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 21.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .32-1.011l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                            </svg>
                        @endif
                    </button>
                    <button wire:click.stop="archiveNote({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-gray-500 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                        </svg>
                    </button>
                    <button wire:click.stop="deleteNote({{ $noteItem->id }})"
                            class="text-gray-800 dark:text-white hover:text-red-800 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                        </svg>
                    </button>
                </div>
            </li>
        @empty
            <li class="text-gray-700 p-2">No notes to show.</li>
        @endforelse
    </ul>
</div>