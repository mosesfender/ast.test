/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */
class TSpoiler extends HTMLDivElement {
    constructor() {
        super(...arguments);
        this.expanded = false;
    }
    connectedCallback() {
        this.expandButton = document.createElement('span');
        this.expandButton.classList.add('expand-button', 'si', 'si-arrow-right-double');
        this.expandButton.style.top = this.offsetTop + 'px';
        this.expandButton.addEventListener('click', (ev) => {
            this.toggle();
        });
        this.insertAdjacentElement('afterend', this.expandButton);
    }
    toggle() {
        this.expanded ? this.collapse() : this.expand();
    }
    expand() {
        this.classList.add('expanded');
        this.expanded = true;
    }
    collapse() {
        this.classList.remove('expanded');
        this.expanded = false;
    }
}
customElements.define('svs-spoiler', TSpoiler, { extends: 'div' });
