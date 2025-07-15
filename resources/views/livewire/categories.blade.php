<div class="px-5 py-5 bg-emerald-300/50 basis-3/12 rounded space-y-2">
    <div class="flex justify-between items-center">
        <h1>Categories</h1>
        <button wire:click="toggleCreateForm">+</button>
    </div>

    @if($showCreateForm)
        <form wire:submit.prevent="createCategory" class="flex space-x-1">
            <input type="text" wire:model.defer="newCategoryName" placeholder="New Category"
                   class="border rounded px-2 py-1 w-full text-gray-900">
            <button type="submit" class="bg-emerald-500 text-white px-3 rounded">+</button>
        </form>
    @endif

    <ul>
        @foreach($categories as $category)
            <li wire:key="{{ $category->id }}"
                wire:click="selectCategory({{ $category->id }})"
                class="py-1 px-1 bg-emerald-400/50 border border-emerald-400 hover:bg-emerald-400 cursor-pointer rounded mb-2
                @if($selectedCategoryId === $category->id) bg-emerald-600 @endif">
                {{ $category->name }}
            </li>
        @endforeach
    </ul>
</div>
