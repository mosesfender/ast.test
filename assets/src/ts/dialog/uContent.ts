/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

module svsdlg {
    export enum TContentEvents {
        beforeContentLoad = 'beforeContentLoad',
        afterContentLoad = 'afterContentLoad',
        iframeLoaded = 'iframeLoaded',
    };

    export interface IContentConstructor {
        new(val: svsdlg.IContentOptions): svsdlg.TContent;
    }

    export interface IContentOptions {
        class: IContentConstructor;
        cssClass?: string[];
        data?: string | string[] | HTMLElement | NodeListOf<HTMLElement>;
        arrayLinesSeparator?: string;
        url?: string;
        fetchMethod?: string;
    }

    export class TContent extends HTMLDivElement {
        public arrayLinesSeparator: string = ' ';

        constructor(options: IContentOptions) {
            super();
            Object.assign(this, options);
        }

        set cssClass(val: any) {
            this.classList.add.apply(this.classList, val);
        }

        set data(val: string | string[] | HTMLElement | NodeListOf<HTMLElement>) {
            this.dispatchEvent(new CustomEvent(TContentEvents.beforeContentLoad, {detail: this}));
            this.clear();
            if (typeof val == 'string') {
                this.innerHTML = val;
            } else if (Array.isArray(val)) {
                this.innerHTML = val.join(this.arrayLinesSeparator);
            } else if (val instanceof HTMLElement) {
                this.appendChild(val);
            } else if (val instanceof NodeList) {
                val.forEach((el: HTMLElement) => {
                    this.appendChild(el);
                });
            }
            this.dispatchEvent(new CustomEvent(TContentEvents.afterContentLoad, {detail: this}));
        }

        protected clear() {
            this.innerHTML = '';
        }
    }

    export class TContentHTML extends TContent {
    }

    export class TContentIFrame extends TContent {
        constructor(options: IContentOptions) {
            super(options);
            let frame = document.createElement('iframe');
            this.appendChild(frame);
            frame.src = options.url;
            frame.addEventListener('load', (ev: Event) => {
                this.dispatchEvent(new CustomEvent(TContentEvents.iframeLoaded, {detail: this.frame.contentDocument}));
            });
        }

        connectedCallback() {
            this.eventListener(TContentEvents.iframeLoaded, (ev: CustomEvent) => {
                console.log(ev.detail);
            });
        }

        get frame() {
            return this.firstElementChild as HTMLIFrameElement;
        }

        get contentDocument() {
            return this.frame.contentDocument;
        }

        get contentWindow() {
            return this.frame.contentWindow;
        }
    }

    export class TContentWeb extends TContent {
    }
}

customElements.define('svs-content', svsdlg.TContent, {extends: 'div'});
customElements.define('svs-content-html', svsdlg.TContentHTML, {extends: 'div'});
customElements.define('svs-content-web', svsdlg.TContentWeb, {extends: 'div'});
customElements.define('svs-content-frame', svsdlg.TContentIFrame, {extends: 'div'});