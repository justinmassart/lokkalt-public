<div id="popups">
    @script
        <script>
            $wire.on('newPopup', () => {
                let popups = document.getElementById('popups');
                let popup = document.createElement('div');
                popup.setAttribute('x-data', '{ open: true }');
                popup.setAttribute('class', 'popup__anim');
                popup.innerHTML = `
        <div class="popup popup__${event.detail.type}" x-show="open"x-transition:scale.origin.top
        style="z-index: ${10000 + popups.children.length}; margin-top: ${0 + popups.children.length}rem">
            <p class="popup__message">${event.detail.message}</p>
            <x-icon class="popup__icon" name="heroicon-o-x-circle" @click="open = false" />
        </div>
    `;
                popups.appendChild(popup);
                requestAnimationFrame(() => {
                    popup.classList.add('popup__fade-in');
                });
                setTimeout(() => {
                    popup.classList.remove('popup__fade-in');
                    popup.classList.add('popup__fade-out');
                }, 5000);
                setTimeout(() => {
                    popup.remove();
                }, 5500);
            });
        </script>
    @endscript
</div>
