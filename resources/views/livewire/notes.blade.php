<div x-data="{ showTrash: @entangle('showTrash'), showArchive: @entangle('showArchive') }"
     class="px-5 py-5 bg-pink-300/50 basis-4/13 rounded space-y-2 flex flex-col overflow-hidden">
    <div class="flex justify-between items-center">
        <h1>{{ $categoryName }}</h1>
        <button wire:click="$dispatch('clearEditor')">+</button>
    </div>

    <ul class="flex-grow overflow-y-auto">
        @forelse($notes as $noteItem)
            <li wire:key="{{ $noteItem->id }}"
                class="py-1 px-1 bg-pink-500/50 border border-pink-500/50 hover:bg-pink-700/80 cursor-pointer rounded mb-2 flex justify-between items-center transition duration-300
                {{ $noteItem->id == $selectedNoteId ? 'bg-pink-800/80' : '' }}">
                <span wire:click="selectNote({{ $noteItem->id }})"
                      class="w-full">
                    {{ Str::limit($noteItem->title ?: 'Başlıksız Not', 15) }}
                </span>
                <div class="flex space-x-1">
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

    <div class="border-t pt-2 mt-auto flex">
        <div x-show="showArchive"
             @click.away="showArchive = false"
             x-transition
             :class="{ 'w-full': showArchive }"
             class="bg-gray-800/50 border border-gray-500 rounded px-2 py-2 mb-2">

            <h2 class="text-gray-200 font-semibold">Archived Notes</h2>
            <ul>
                @forelse($archivedNotes as $archivedNote)
                    <li wire:key="archived-{{ $archivedNote->id }}"
                        class="py-1 px-1 bg-gray-400/50 border border-gray-400 hover:bg-gray-400/50 cursor-pointer rounded mb-2 flex justify-between items-center">
                        <span>{{ Str::limit($archivedNote->title ?: 'Başlıksız Not', 15) }}</span>
                        <div class="flex space-x-1">
                            <button wire:click="unarchiveNote({{ $archivedNote->id }})"
                                    class="dark:hover:text-gray-600  hover:text-white transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                                </svg>
                            </button>
                            <button wire:click="permanentDeleteFromArchive({{ $archivedNote->id }})"
                                    class=" hover:text-red-700 dark:text-white text-black transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="dark:text-gray-400 text-gray-300">No notes to show.</li>
                @endforelse
            </ul>
        </div>
        <div x-show="showTrash"
             @click.away="showTrash = false"
             x-transition
             :class="{ 'w-full': showTrash }"
             class="bg-pink-800/50 border border-pink-500 rounded px-2 py-2 mb-2">

            <h2 class="text-gray-200 font-semibold">Deleted Notes</h2>
            <ul>
                @forelse($trashedNotes as $trashedNote)
                    <li wire:key="trashed-{{ $trashedNote->id }}"
                        class="py-1 px-1 bg-pink-400/50 border border-pink-400 hover:bg-pink-400/50 cursor-pointer rounded mb-2 flex justify-between items-center">
                        <span>{{ Str::limit($trashedNote->title ?: 'Başlıksız Not', 15) }}</span>
                        <div class="flex space-x-1">
                            <button wire:click="restoreNote({{ $trashedNote->id }})"
                                    class="hover:text-green-300 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </button>
                            <button wire:click="permanentDeleteNote({{ $trashedNote->id }})"
                                    class=" hover:text-red-700 dark:text-white text-black  transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="dark:text-gray-400 text-gray-300">No notes to show.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="flex space-x-2 mt-2">
        <button @click="showTrash = !showTrash"
                class="inline-flex items-center px-4 py-2 dark:bg-rose-700 dark:hover:bg-rose-600 bg-rose-600 hover:bg-rose-500 rounded w-1/2 h-[35px] text-white transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
            </svg>
            Trash
        </button>
        <button @click="showArchive = !showArchive"
                class="inline-flex items-center px-4 py-2 dark:bg-gray-700 dark:hover:bg-gray-600 bg-gray-600 hover:bg-gray-500 rounded w-1/2 h-[35px] text-white transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
            </svg>
            Archive
        </button>
    </div>
</div>
