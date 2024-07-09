/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

let ttInit = () => {
    $('[data-toggle="tooltip"]').tooltip();
}

let languageChoose = function () {
    this.element = document.querySelector('.lev.lev-language');
    [].map.call(this.element.querySelectorAll('.language'), (el: HTMLElement) => {
        el.addEventListener('click', (ev: PointerEvent) => {
            let lang = (ev.target as HTMLElement).getAttribute('data-lang');
            cookie.write('ast.lang', lang);
            location.reload();
        });
    });
}

let themeChoose = function () {
    this.element = document.querySelector('.lev.lev-theme');
    [].map.call(this.element.querySelectorAll('.theme'), (el: HTMLElement) => {
        el.addEventListener('click', (ev: PointerEvent) => {
            let theme = (ev.target as HTMLElement).getAttribute('data-theme');
            cookie.write('ast.theme', theme);
            document.querySelector('html').setAttribute('data-bs-theme', theme);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    ttInit();
    themeChoose();
    languageChoose();

    $(document).on('pjax:complete', (ev: JQuery.Event)=>{
        ttInit();
    });
});