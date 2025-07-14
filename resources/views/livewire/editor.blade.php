<div class="border border-emerald-600 rounded overflow-hidden relative basis-5/12">

    <input
            type="text"
            wire:model.live.debounce.1000ms="title"
            @keydown.enter.prevent="$refs.body.focus()"
            placeholder="Başlık"
            class="bg-emerald-600/50 w-full px-4 py-3 focus:outline-none focus:ring-0 text-xl placeholder-gray-300"
    >

    <textarea
            x-ref="body"
            wire:model.live.debounce.1000ms="body"
            placeholder="Bir şeyler yaz..."
            class="bg-emerald-600/50 w-full px-4 py-2 min-h-[600px] focus:outline-none focus:ring-0 placeholder-gray-300"
    ></textarea>
</div>