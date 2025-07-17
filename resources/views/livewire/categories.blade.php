<div x-data="{ showForm: false }" x-init="window.addEventListener('categoryCreated', () => { showForm = false })"
     class="px-5 py-5 bg-sky-300/50 basis-4/12 rounded space-y-2 flex flex-col overflow-hidden">
    <div class="flex justify-between items-center">
        <h1>Categories</h1>
        <button @click="showForm = !showForm; if(showForm) { $nextTick(() => $refs.nameInput.focus()) }"
                class="hover:text-gray-400 transition duration-300">+
        </button>
    </div>

    <form x-show="showForm" @submit.prevent="$wire.createCategory()" class="flex space-x-1 mt-2" x-cloak>
        <input x-ref="nameInput" type="text" wire:model.defer="newCategoryName" placeholder="Name"
               class="border rounded px-2 py-1 w-full text-gray-900">
        <button type="submit"
                class="bg-sky-500/80 hover:bg-sky-700/80 text-white px-3 rounded transition duration-300">+
        </button>
    </form>

    <ul class="flex-grow overflow-y-auto">
        @forelse($categories as $category)
            <li wire:key="{{ $category->id }}"
                class="group py-1 px-1 bg-sky-400/50 border border-sky-400/50 hover:bg-sky-600/80 cursor-pointer rounded mb-2 flex justify-between items-center transition duration-300
                @if($selectedCategoryIdInt === $category->id) bg-sky-700/80 hover:bg-sky-600 @endif">
                <span wire:click="selectCategory({{ $category->id }})" class="w-full">{{ $category->name }}</span>
                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition duration-300">
                    <button wire:click.stop="archiveCategory({{ $category->id }})"
                            class="text-gray-800 dark:text-white hover:text-gray-500 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                        </svg>
                    </button>
                    <button wire:click.stop="deleteCategory({{ $category->id }})"
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
            <li class=" text-gray-700 p-2">No categories to show.</li>
        @endforelse
    </ul>
</div>