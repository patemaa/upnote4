<div class="px-5 py-5 bg-emerald-300/50 basis-3/12 rounded">
    <ul>
        @foreach($categories as $category)
            <li class="py-1 px-1 bg-emerald-400/50 border border-emerald-400 hover:bg-emerald-400 cursor-pointer rounded mb-2">
                {{ $category->name }}
            </li>
        @endforeach
    </ul>
</div>