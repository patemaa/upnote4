<div class="px-5 py-5 bg-emerald-500/50 basis-4/12 rounded">
    <ul>
        @foreach($notes as $note)
            <li class="py-1 px-1 bg-emerald-500/50 border border-emerald-500 hover:bg-emerald-500 cursor-pointer rounded mb-2">
                {{ $note->title }}
            </li>
        @endforeach
    </ul>
</div>