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
            <button wire:click="togglePinned"
                    class="px-4 py-2 transition duration-300 bg-purple-500/50 dark:bg-purple-400/50 text-white rounded hover:bg-purple-600/50">
                @if($showPinned)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                         class="icon icon-tabler icons-tabler-outline icon-tabler-pin">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4"/>
                        <path d="M9 15l-4.5 4.5"/>
                        <path d="M14.5 4l5.5 5.5"/>
                    </svg>
                @endif
            </button>
            <button wire:click="toggleFavorites"
                    class="px-4 py-2 transition duration-300 bg-yellow-500/50 dark:bg-yellow-400/50 text-white rounded hover:bg-yellow-600/50">
                @if($showFavorites)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.32 1.011l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 21.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .32-1.011l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                    </svg>
                @endif
            </button>
            <button wire:click="toggleArchive"
                    class="px-4 py-2 transition duration-300 bg-blue-600/50 dark:bg-blue-500/50 text-white rounded hover:bg-blue-600/50">
                @if($showArchive)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                    </svg>
                @endif
            </button>
            <button wire:click="toggleTrash"
                    class="px-4 py-2 transition duration-300 bg-red-600/50 dark:bg-red-500/50 text-white rounded hover:bg-red-600/50">
                @if($showTrash)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                @endif
            </button>
        </div>
    </div>

    <div>
        @if ($showTrash)
            <div class="p-4 bg-gray-200 rounded shadow min-h-[300px]">
                <div class="flex justify-between">
                    <h2 class="text-2xl font-bold text-red-600">Çöp Kutusu</h2>
                    <div class="flex justify-between space-x-2">
                        <button wire:click="emptyTrash"
                                wire:confirm="Çöp kutusunu boşaltmak istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition">
                            Tümünü Sil
                        </button>

                        <button wire:click="restoreAllNotes"
                                onclick="return confirm('Tüm kategorileri ve notları geri yüklemek istediğinizden emin misiniz?')"
                                class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800 transition">
                            Tümünü Geri Yükle
                        </button>
                    </div>

                </div>

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Silinmiş Kategoriler</h3>
                @forelse($trashedCategories as $category)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $category->name }}</span>
                        <div>
                            <button wire:click="restoreCategory({{ $category->id }})"
                                    class="text-green-600 hover:text-green-800 font-semibold transition">Geri Yükle
                            </button>
                            <button wire:click="forceDeleteCategory({{ $category->id }})"
                                    wire:confirm="Bu kategoriyi kalıcı olarak silmek istediğinizden emin misiniz? İlişkili tüm notlar da silinecektir!"
                                    class="ml-4 text-red-600 hover:text-red-800 font-semibold transition">Kalıcı Sil
                            </button>
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
                            <button wire:click="restoreNote({{ $note->id }})"
                                    class="text-green-600 hover:text-green-800 font-semibold transition">Geri Yükle
                            </button>
                            <button wire:click="forceDeleteNote({{ $note->id }})"
                                    wire:confirm="Bu notu kalıcı olarak silmek istediğinizden emin misiniz?"
                                    class="ml-4 text-red-600 hover:text-red-800 font-semibold transition">Kalıcı Sil
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Silinmiş not bulunmuyor.</p>
                @endforelse
            </div>

        @elseif($showArchive)
            <div class="p-4 bg-gray-200 rounded shadow min-h-[300px]">
                <div class="flex justify-between">
                    <h2 class="text-2xl font-bold text-blue-600/80">Arşiv</h2>
                    <div class="flex justify-between space-x-2">
                        <button wire:click="emptyTrash"
                                wire:confirm="Arsivdekileri silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition">
                            Tümünü Sil
                        </button>
                        <button wire:click="emptyArchive"
                                onclick="return confirm('Arşivi boşaltmak istediğinizden emin misiniz?')"
                                class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 transition">
                            Arşivi Boşalt
                        </button>
                    </div>
                </div>

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Arşivlenmiş Kategoriler</h3>
                @forelse($archivedCategories as $category)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $category->name }}</span>
                        <button wire:click="unarchiveCategory({{ $category->id }})"
                                class="text-blue-500 hover:text-blue-700 font-semibold transition">Arşivden Çıkar
                        </button>
                    </div>
                @empty
                    <p class="text-gray-500">Arşivlenmiş kategori bulunmuyor.</p>
                @endforelse

                <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-700">Arşivlenmiş Notlar</h3>
                @forelse($archivedNotes as $note)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $note->title }}</span>
                        <button wire:click="unarchiveNote({{ $note->id }})"
                                class="font-semibold transition text-blue-500 hover:text-blue-700">Arşivden Çıkar
                        </button>
                    </div>
                @empty
                    <p class="text-gray-500">Arşivlenmiş not bulunmuyor.</p>
                @endforelse
            </div>
        @elseif($showFavorites)
            <div class="p-4 bg-gray-200 rounded shadow min-h-[300px]">
                <div class="flex justify-between">
                    <h2 class="text-2xl font-bold text-yellow-600 mb-2">Favoriler</h2>
                    <button wire:click="emptyFavorites"
                            wire:confirm="Favorileri kaldirmak istediginizden emin misiniz?"
                            class="px-4 py-2 bg-yellow-700 text-white rounded hover:bg-yellow-800 transition">
                        Favorileri kaldir
                    </button>
                </div>

                @forelse($favoritedNotes as $note)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $note->title }}</span>
                        <button wire:click="unfavoriteNote({{ $note->id }})"
                                class="text-yellow-600 hover:text-yellow-800 font-semibold transition">Favorilerden
                            Çıkar
                        </button>
                    </div>
                @empty
                    <p class="text-gray-500">Favori not bulunmuyor.</p>
                @endforelse
            </div>
        @elseif($showPinned)
            <div class="p-4 bg-gray-200 rounded shadow min-h-[300px]">
                <div class="flex justify-between">
                    <h2 class="text-2xl font-bold text-purple-600/80 mb-2">Sabitlenenler</h2>
                    <button wire:click="emptyPinned"
                            wire:confirm="Sabitlenenleri kaldirmak istediginizden emin misiniz?"
                            class="px-4 py-2 bg-purple-700 text-white rounded hover:bg-purple-800 transition">
                        Sabitlenenleri kaldir
                    </button>
                </div>
                @forelse($pinnedNotes as $note)
                    <div class="flex justify-between items-center p-2 border-b">
                        <span>{{ $note->title }}</span>
                        <button wire:click="unpinNote({{ $note->id }})"
                                class="text-purple-600 hover:text-purple-800 font-semibold transition">Sabitlemeyi
                            Kaldır
                        </button>
                    </div>
                @empty
                    <p class="text-gray-500">Sabitlenmiş not bulunmuyor.</p>
                @endforelse
            </div>
        @else
            <div class="text-gray-900 dark:text-gray-100 flex min-h-[400px] space-x-2">
                <livewire:categories :search="$search"/>
                <livewire:notes/>
                <livewire:editor/>
            </div>
        @endif
    </div>
</div>