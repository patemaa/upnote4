<div class="px-5 py-5 bg-sky-300/50 basis-4/12 rounded space-y-2">
    <div class="flex justify-between items-center">
        <h1>Kategoriler</h1>
        <button wire:click="toggleCreateForm">+</button>
    </div>

    @if($showCreateForm)
        <form wire:submit.prevent="createCategory" class="flex space-x-1">
            <input type="text" wire:model.defer="newCategoryName" placeholder="Yeni Kategori"
                   class="border rounded px-2 py-1 w-full text-gray-900">
            <button type="submit" class="bg-sky-500/80 hover:bg-sky-500/50 text-white px-3 rounded">+</button>
        </form>
    @endif

    <ul>
        @foreach($categories as $category)
            <li wire:key="{{ $category->id }}"
                class="py-1 px-1 bg-sky-400/50 border border-sky-400/50 hover:bg-sky-500/50 cursor-pointer rounded mb-2 flex justify-between items-center
                @if($selectedCategoryId === $category->id) bg-sky-600 hover:bg-sky-700 @endif">
                <span wire:click="selectCategory({{ $category->id }})" class="w-full">{{ $category->name }}</span>
                <button wire:click.stop="deleteCategory({{ $category->id }})" class="text-white hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
            </li>
        @endforeach
    </ul>

    <div class="border-t pt-2">
        <button wire:click="toggleTrash"
                class="inline-flex items-center px-4 py-2 bg-rose-700 hover:bg-rose-600 rounded w-full h-[35px]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
            </svg>
            Çöp Kutusu
        </button>

        @if($showTrash)
            <div class="bg-sky-800/50 border border-sky-500 rounded px-2 py-2 mt-2">

                <h2>Silinen Kategoriler</h2>
                <ul>
                    @foreach($trashedCategories as $trashedCategory)
                        <li wire:key="trashed-{{ $trashedCategory->id }}"
                            class="py-1 px-1 bg-sky-400/50 border border-sky-400 hover:bg-sky-400/50 cursor-pointer rounded mb-2 flex justify-between items-center">
                            <span>{{ $trashedCategory->name }}</span>

                            <div class="flex space-x-1">
                                <button wire:click="restoreCategory({{ $trashedCategory->id }})"
                                        class="hover:text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                </button>

                                <button wire:click="permanentDeleteCategory({{ $trashedCategory->id }})"
                                        class="text-white hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                    @endforeach
                    @if($trashedCategories->isEmpty())
                        <li class=" text-gray-500">No categories to show</li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
</div>