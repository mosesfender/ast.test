/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

module svsdlg {
    export class TDialog extends TPopup {

        protected _lever: TDialogLever;
        protected _buttons: TDialogButtons;

        public modal: boolean = false;
        public closeOnClickOutside: boolean = false;

        constructor(options?: IDefaultSettings, defs?: IDefaultSettings) {
            super(options, defs);
            if (!options) {
                options = defDialog;
            }
        }

        set lever(val: IDialogLeverOptions) {
            this._lever = new svsdlg.TDialogLever(val);
        }

        get lever() {
            return this._lever;
        }

        /**
         * Provide TLever.caption
         * @param val
         */
        set caption(val: string){
            this.lever.caption = val;
        }

        protected render() {
            this._lever ? this.appendChild(this._lever) : null;
            this._content ? this.appendChild(this._content) : null;
            this._buttons ? this.appendChild(this._buttons) : null;
        }
    }

    export interface IDialogLeverOptions {
        caption?: string;
        captionIcon?: string | boolean; // CSS class or bool
        buttons?: number; // Вариации из TLeverButtons
    }

    export class TDialogLever extends HTMLDivElement {
        protected _icon: HTMLImageElement;
        protected _caption: HTMLSpanElement;

        constructor(options?: IDialogLeverOptions) {
            super();
            Object.assign(this, options);
            this.render();
        }

        set caption(val: string) {
            if (!this._caption) {
                this._caption = document.createElement('span');
                this._caption.classList.add('dlg-caption');
            }
            this._caption.textContent = val;
        }

        set captionIcon(val: string | boolean) {
            if (!this._icon) {
                this._icon = document.createElement('img');
            }
            if (typeof val == 'string') {
                this._icon.classList.add(val);
            }
        }

        get dialog() {
            return this.closestType(TPopup);
        }

        render() {
            this._icon ? this.append(this._icon) : null;
            this._caption ? this.append(this._caption) : null;
        }
    }

    export class TDialogButtons extends HTMLDivElement {

    }

    export class TDialogError extends TDialog {
        constructor(options?: IDefaultSettings) {
            super(options ?? defErrorDialog);
        }
    }
}
customElements.define('svs-dialog', svsdlg.TDialog, {extends: 'div'});
customElements.define('svs-dialog-lever', svsdlg.TDialogLever, {extends: 'div'});
customElements.define('svs-dialog-buttons', svsdlg.TDialogButtons, {extends: 'div'});

customElements.define('svs-dialog-error', svsdlg.TDialogError, {extends: 'div'});
