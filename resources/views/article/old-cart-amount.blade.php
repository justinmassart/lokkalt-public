<div class="article__top__infos__actions" x-data="{ itemsCount: @entangle('itemsCount'), maxItems: @entangle('maxItems') }">
    {{--  <div class="button-count">
        <button type="button" @click="itemsCount <= 1 ? itemsCount = 1 : itemsCount--"
            class="button-count__minus"><x-icons name="minus" /></button>
        <input x-model='itemsCount'
            @change="itemsCount <= 1 ? itemsCount = 1 : null; itemsCount >= maxItems ? itemsCount = maxItems : null"
            max="maxItems" name="articles_count" id="articles_count_input" class="button-count__number"
            type="number" />
        <button type="button" @click="itemsCount >= maxItems ? itemsCount = maxItems : itemsCount++"
            class="button-count__plus"><x-icons name="plus" /></button>
    </div>
    <div class="hidden" :class="{ 'hidden': itemsCount !== maxItems }">
        <p>This article has only {!! $maxItems !!} quantity in stock.</p>
    </div> --}}
    <div class="article__top__infos__actions__btns">
        <x-button type="submit" style="outlined">Ajouter au
            panier</x-button>
        <x-button wire:click.prevent='buyNow'>Acheter maintenant</x-button>
    </div>
    </form>
