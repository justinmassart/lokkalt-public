@props(['icon' => null])
<div class="main__title-container">
    @if (true === false && $icon)
        <x-icons name="{!! $icon !!}" folder="titles" />
    @endif
    <h2 class="main__title main-title">{!! ucfirst($slot) !!}</h2>
</div>
