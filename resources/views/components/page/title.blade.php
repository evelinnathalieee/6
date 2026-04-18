@props(['title' => '', 'subtitle' => null, 'actions' => null])

<div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-zinc-900">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm text-zinc-600">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($actions)
        <div>{{ $actions }}</div>
    @endif
</div>
