@props(['name', 'folder' => null, 'livewire' => null])
<div class="icon-container" {!! $livewire ? "$livewire" : null !!}>
    {!! file_get_contents(public_path('storage/svg/' . ($folder ? $folder . '/' . $name : $name) . '.svg')) ?? '' !!}
</div>
