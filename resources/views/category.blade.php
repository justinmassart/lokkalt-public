<x-layouts.app>
    @section('pageTitle', $category->name)
    <x-main-title icon="food">@lang('categories.' . str()->slug($category->name)){!! $selectedSubCategory ? ' | ' . __('categories.' . str()->slug($selectedSubCategory->name)) : null !!}</x-main-title>
    @if ($category->sub_categories->isNotEmpty())
        <section class="quick-sub-categories">
            <div class="quick-sub-categories__top section__top">
                <h2 class="quick-sub-categories__top__title section__title-nm">@lang('titles.quick_sub_categories')</h2>
            </div>
            <div class="quick-sub-categories__list grid">
                @foreach ($category->sub_categories as $index => $subCategory)
                    <x-cards.category-small key="{!! $index !!}" category_name="{!! $category->name !!}"
                        sub_category_name="{!! $subCategory->name !!}" />
                @endforeach
            </div>
        </section>
    @endif
    <section class="results" x-data="{ subCategoryModal: false }">
        <livewire:category.category-articles-list :category="$category" :subCategory="$selectedSubCategory" lazy="on-load" />
        <script>
            document.addEventListener('livewire:navigated', function() {
                Alpine.nextTick(() => {
                    window.scrollToElement = function(selector) {
                        const element = document.querySelector(selector);
                        if (element && element.parentElement) {
                            const yCoordinate = element.parentElement.getBoundingClientRect().top + window
                                .pageYOffset;
                            const yOffset = -20;
                            window.scrollTo({
                                top: yCoordinate + yOffset,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        </script>
    </section>
</x-layouts.app>
