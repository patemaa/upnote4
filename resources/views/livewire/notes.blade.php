<div x-data="{ showTrash: @entangle('showTrash') }"
     class="px-5 py-5 bg-pink-300/50 basis-4/12 rounded space-y-2 flex flex-col">
    <div class="flex justify-between items-center">
        <h1>{{ $categoryName }}</h1>
        <button wire:click="$dispatch('clearEditor')">+</button>
    </div>

    <div class="relative">
        <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search note"
                class="w-full px-3 py-1 rounded-md border border-pink-400/50 bg-pink-300/30 focus:outline-none focus:ring-1 focus:ring-pink-800/80 text-gray-800 placeholder-gray-200"
        >
    </div>
    <ul class="flex-grow overflow-y-auto">
        @forelse($notes as $noteItem)
            <li wire:key="{{ $noteItem->id }}"
                class="py-1 px-1 bg-pink-500/50 border border-pink-500/50 hover:bg-pink-600/50 cursor-pointer rounded mb-2 flex justify-between items-center
                {{ $noteItem->id == $selectedNoteId ? 'bg-pink-700/50' : '' }}">
                <span wire:click="selectNote({{ $noteItem->id }})"
                      class="w-full">
                    {{ Str::limit($noteItem->title ?: 'Başlıksız Not', 25) }}
                </span>
                <button wire:click.stop="deleteNote({{ $noteItem->id }})" class="text-white hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
            </li>
        @empty
            <li class=" text-gray-700 p-2">Gösterilecek not bulunamadı.</li>
        @endforelse
    </ul>


    <div class="border-t pt-2 mt-auto">
        <div x-show="showTrash"
             @click.away="showTrash = false"
             x-transition class="bg-pink-800/50 border border-pink-500 rounded px-2 py-2 mb-2">

            <h2>Silinen Notlar</h2>
            <ul>
                @forelse($trashedNotes as $trashedNote)
                    <li wire:key="trashed-{{ $trashedNote->id }}"
                        class="py-1 px-1 bg-pink-400/50 border border-pink-400 hover:bg-pink-400/50 cursor-pointer rounded mb-2 flex justify-between items-center">
                        <span>{{ $trashedNote->title }}</span>

                        <div class="flex space-x-1">
                            <button wire:click="restoreNote({{ $trashedNote->id }})" class="hover:text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </button>

                            <button wire:click="permanentDeleteNote({{ $trashedNote->id }})"
                                    class="text-white hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @empty
                    <li class=" text-gray-500">No notes to show</li>
                @endforelse
            </ul>
        </div>
        <button @click="showTrash = !showTrash"
                class="inline-flex items-center px-4 py-2 bg-rose-700 hover:bg-rose-600 rounded w-full h-[35px] ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
            </svg>
            Çöp Kutusu
        </button>
    </div>
</div>