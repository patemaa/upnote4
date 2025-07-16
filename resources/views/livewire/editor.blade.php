<div x-data="{ showDropdown: @entangle('showCategoryDropdown').defer }"
     class="relative rounded overflow-hidden basis-5/13 bg-purple-300/50 px-1 py-1">

    <div class="flex justify-between items-center">
        <input
                type="text"
                wire:model.live.debounce.1000ms="title"
                @keydown.enter.prevent="$refs.body && $refs.body.focus()"
                placeholder="Title"
                class="bg-transparent w-full px-4 py-3 focus:outline-none focus:ring-0 text-xl dark:placeholder-gray-300 placeholder-gray-800"
        >
        <button @click="showDropdown = !showDropdown"
                class="transition duration-300 text-gray-800 dark:text-white text-[12px] bg-purple-400/50 hover:bg-purple-400/80 rounded mr-2">
            Choose Category
        </button>
    </div>

    <div
            x-show="showDropdown"
            x-transition
            @click.away="showDropdown = false"
            class="absolute right-2 top-14 bg-white rounded shadow p-2 w-48 z-50"
            style="display: none"
    >
        @foreach($categories as $category)
            <button
                    wire:click="assignCategory({{ $category->id }})"
                    @click="showDropdown = false"
                    class="w-full text-left px-3 py-1 rounded hover:bg-purple-100 text-black flex justify-between items-center @if($noteCategoryId == $category->id) bg-purple-200 @endif"
                    type="button"
            >
                {{ $category->name }}
                @if($noteCategoryId == $category->id)
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    <textarea
            x-ref="body"
            wire:model.live="body"
            placeholder="Enter Something..."
            class="bg-transparent w-full px-4 py-2 min-h-[400px] focus:outline-none focus:ring-0 dark:placeholder-gray-300 placeholder-gray-800"
    ></textarea>

    <div class="px-2 dark:text-white/70 text-gray-900">
        <p>Kelime Sayısı : {{ str_word_count($body) }}</p>
    </div>
</div>