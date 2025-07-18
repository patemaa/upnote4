<div>
    <div class="px-[100px] p-4 flex justify-between items-center relative">
        <div class="relative w-[905px]">
            <input type="text" wire:model.live="search" placeholder="Search..."
                   class="w-full p-2 border rounded">

            @if (strlen(trim($search)) >= 1)
                <div class="absolute left-0 top-full mt-1 w-full bg-white border border-gray-300 rounded shadow-lg z-50">
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
        </div>

        <div class="flex space-x-2 ml-4">
            <button wire:click="toggleArchive" class="px-4 py-2 transition duration-300 bg-blue-600/50 dark:bg-blue-500/50 text-white rounded hover:bg-blue-600/50">
                @if($showArchive)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                @endif
            </button>
            <button wire:click="toggleTrash" class="px-4 py-2 transition duration-300 bg-red-600/50 dark:bg-red-500/50 text-white rounded hover:bg-red-600/50">
                @if($showTrash)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                @endif
            </button>
        </div>
    </div>

    <div>
        @if ($showTrash)
            <div class="p-4 bg-gray-200 rounded shadow">
               <div class="flex justify-between">
                   <h2 class="text-2xl font-bold text-red-600">Çöp Kutusu</h2>
                   <button wire:click="emptyTrash" wire:confirm="Çöp kutusunu boşaltmak istediginizden emin misiniz? Bu işlem geri alınamaz."
                           class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition">
                       Çöp Kutusunu Boşalt
                   </button>
               </div>

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Silinmiş Kategoriler</h3>
                @forelse($trashedCategories as $category)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $category->name }}</span>
                        <div>
                            <button wire:click="restoreCategory({{ $category->id }})" class="text-green-600 hover:text-green-800 font-semibold transition">Geri Yükle</button>
                            <button wire:click="forceDeleteCategory({{ $category->id }})" wire:confirm="Bu kategoriyi kalıcı olarak silmek istediğinizden emin misiniz? İlişkili tüm notlar da silinecektir!" class="ml-4 text-red-600 hover:text-red-800 font-semibold transition">Kalıcı Sil</button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Silinmiş kategori bulunmuyor.</p>
                @endforelse

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Silinmiş Notlar</h3>
                @forelse($trashedNotes as $note)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $note->title }}</span>
                        <div>
                            <button wire:click="restoreNote({{ $note->id }})" class="text-green-600 hover:text-green-800 font-semibold transition">Geri Yükle</button>
                            <button wire:click="forceDeleteNote({{ $note->id }})" wire:confirm="Bu notu kalıcı olarak silmek istediğinizden emin misiniz?" class="ml-4 text-red-600 hover:text-red-800 font-semibold transition">Kalıcı Sil</button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Silinmiş not bulunmuyor.</p>
                @endforelse
            </div>

        @elseif($showArchive)
            <div class="p-4 bg-gray-200 rounded shadow">
                <div class="flex justify-between">
                    <h2 class="text-2xl font-bold text-blue-600/80">Arşiv</h2>
                    <button wire:click="emptyArchive"
                            onclick="return confirm('Arşivi boşaltmak istediğinizden emin misiniz? Buradan silinen notları çöp kutusunda bulabilirsiniz.')"
                            class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 transition">
                        Arşivi Boşalt
                    </button>
                </div>

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Arşivlenmiş Kategoriler</h3>
                @forelse($archivedCategories as $category)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $category->name }}</span>
                        <button wire:click="unarchiveCategory({{ $category->id }})" class="text-blue-500 hover:text-blue-700 font-semibold transition">Arşivden Çıkar</button>
                    </div>
                @empty
                    <p class="text-gray-500">Arşivlenmiş kategori bulunmuyor.</p>
                @endforelse

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Arşivlenmiş Notlar</h3>
                @forelse($archivedNotes as $note)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $note->title }}</span>
                        <button wire:click="unarchiveNote({{ $note->id }})" class="font-semibold transition text-blue-500 hover:text-blue-700">Arşivden Çıkar</button>
                    </div>
                @empty
                    <p class="text-gray-500">Arşivlenmiş not bulunmuyor.</p>
                @endforelse
            </div>

        @else
            <div class="text-gray-900 dark:text-gray-100 flex min-h-[400px] space-x-2">
                <livewire:categories :search="$search"/>
                <livewire:notes />
                <livewire:editor/>
            </div>
        @endif
    </div>
</div>