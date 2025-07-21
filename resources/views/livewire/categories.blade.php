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

    <ul x-data
        x-init="
            new Sortable($el, {
                animation: 150,
                ghostClass: 'bg-gray-200/50',
                handle: '.handle',
                onEnd: () => {
                    let ids = Array.from($el.children).map(child => child.getAttribute('wire:key'));
                    $wire.call('updateCategoryOrder', ids);
                }
            });
        "
        class="flex-grow overflow-y-auto"
    >

        @forelse($categories as $category)
            <li wire:key="{{ $category->id }}"
                class="group py-1 px-1 bg-sky-400/50 border border-sky-400/50 hover:bg-sky-600/80 rounded mb-2 flex justify-between items-center transition duration-300 cursor-grab active:cursor-grabbing
        @if($selectedCategoryIdInt === $category->id) bg-sky-700/80 hover:bg-sky-600 @endif">

                <div class="handle text-white cursor-grab opacity-0 group-hover:opacity-100 transition duration-300">
                    @svg('hugeicons-drag-drop-horizontal', 'w-5 h-5')
                </div>

                <span wire:click="selectCategory({{ $category->id }})" class="w-full pl-1">{{ $category->name }}</span>

                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition duration-300">
                    <button wire:click.stop="archiveCategory({{ $category->id }})"
                            class="text-gray-800 dark:text-white hover:text-gray-500 transition duration-300">
                        @svg('icon-archiveSomething', 'size-5')
                    </button>

                    <button wire:click.stop="deleteCategory({{ $category->id }})"
                            class="text-gray-800 dark:text-white hover:text-red-800 transition duration-300">
                        @svg('icon-trash', 'size-5')
                    </button>
                </div>
            </li>
        @empty
            <li class=" text-gray-700 p-2">No categories to show.</li>
        @endforelse
    </ul>
</div>