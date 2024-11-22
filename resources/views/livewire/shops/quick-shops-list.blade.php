<div class="popular-shops__list grid">
    @foreach ($this->shops as $index => $shop)
        <x-cards.shop-small :key="$index" :$shop />
    @endforeach
</div>
