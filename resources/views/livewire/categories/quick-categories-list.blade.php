<div class="popular-categories__list grid">
    @foreach ($this->categories as $index => $category)
        <x-cards.category key={!! $index !!} category_name="{!! $category->name !!}" />
    @endforeach
</div>
