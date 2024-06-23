/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

module svsdlg {
    export class TPopup extends HTMLDivElement {
        protected _content: TContent;

        constructor(options?: IDefaultSettings, defs?: IDefaultSettings) {
            super();
            let _defs = clone(defs);
            extend(_defs, options);
            Object.assign(this, _defs);
            this.render();
        }

        set body(val: IContentOptions) {
            this._content = new val.class(val);
        }

        set cssClass(val: string[]) {
            this.classList.add.apply(this.classList, val);
        }

        protected render() {
            this.append(this._content);
        }

        public show() {
            this.classList.add('show');
        }

        public hide() {
            this.classList.remove('show');
        }
    }
}

customElements.define('svs-popup', svsdlg.TPopup, {extends: 'div'});