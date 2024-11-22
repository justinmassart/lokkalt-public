<div>
    <div class="categories__list">
        @foreach ($this->categories as $index => $category)
            <x-cards.category key={!! $index !!} category_name="{!! $category->name !!}" />
        @endforeach
    </div>
</div>
