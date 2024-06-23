var svsdlg;
(function (svsdlg) {
    let TDialogContentType;
    (function (TDialogContentType) {
        TDialogContentType[TDialogContentType["CT_HTML"] = 2] = "CT_HTML";
        TDialogContentType[TDialogContentType["CT_IFRAME"] = 3] = "CT_IFRAME";
        TDialogContentType[TDialogContentType["CT_WEB"] = 4] = "CT_WEB";
    })(TDialogContentType || (TDialogContentType = {}));
    let TLeverButtons;
    (function (TLeverButtons) {
        TLeverButtons[TLeverButtons["LB_CLOSE"] = 1] = "LB_CLOSE";
        TLeverButtons[TLeverButtons["LB_MAXIMIZE"] = 2] = "LB_MAXIMIZE";
        TLeverButtons[TLeverButtons["LB_MINIMIZE"] = 4] = "LB_MINIMIZE";
    })(TLeverButtons = svsdlg.TLeverButtons || (svsdlg.TLeverButtons = {}));
    svsdlg.defDialog = {
        cssClass: ['svs-dialog'],
        lever: { buttons: TLeverButtons.LB_CLOSE },
    };
    svsdlg.defErrorDialog = {
        cssClass: ['svs-dialog', 'svs-dialog-error'],
    };
})(svsdlg || (svsdlg = {}));
var svsdlg;
(function (svsdlg) {
    let TContentEvents;
    (function (TContentEvents) {
        TContentEvents["beforeContentLoad"] = "beforeContentLoad";
        TContentEvents["afterContentLoad"] = "afterContentLoad";
        TContentEvents["iframeLoaded"] = "iframeLoaded";
    })(TContentEvents = svsdlg.TContentEvents || (svsdlg.TContentEvents = {}));
    ;
    class TContent extends HTMLDivElement {
        constructor(options) {
            super();
            this.arrayLinesSeparator = ' ';
            Object.assign(this, options);
        }
        set cssClass(val) {
            this.classList.add.apply(this.classList, val);
        }
        set data(val) {
            this.dispatchEvent(new CustomEvent(TContentEvents.beforeContentLoad, { detail: this }));
            this.clear();
            if (typeof val == 'string') {
                this.innerHTML = val;
            }
            else if (Array.isArray(val)) {
                this.innerHTML = val.join(this.arrayLinesSeparator);
            }
            else if (val instanceof HTMLElement) {
                this.appendChild(val);
            }
            else if (val instanceof NodeList) {
                val.forEach((el) => {
                    this.appendChild(el);
                });
            }
            this.dispatchEvent(new CustomEvent(TContentEvents.afterContentLoad, { detail: this }));
        }
        clear() {
            this.innerHTML = '';
        }
    }
    svsdlg.TContent = TContent;
    class TContentHTML extends TContent {
    }
    svsdlg.TContentHTML = TContentHTML;
    class TContentIFrame extends TContent {
        constructor(options) {
            super(options);
            let frame = document.createElement('iframe');
            this.appendChild(frame);
            frame.src = options.url;
            frame.addEventListener('load', (ev) => {
                this.dispatchEvent(new CustomEvent(TContentEvents.iframeLoaded, { detail: this.frame.contentDocument }));
            });
        }
        connectedCallback() {
            this.eventListener(TContentEvents.iframeLoaded, (ev) => {
                console.log(ev.detail);
            });
        }
        get frame() {
            return this.firstElementChild;
        }
        get contentDocument() {
            return this.frame.contentDocument;
        }
        get contentWindow() {
            return this.frame.contentWindow;
        }
    }
    svsdlg.TContentIFrame = TContentIFrame;
    class TContentWeb extends TContent {
    }
    svsdlg.TContentWeb = TContentWeb;
})(svsdlg || (svsdlg = {}));
customElements.define('svs-content', svsdlg.TContent, { extends: 'div' });
customElements.define('svs-content-html', svsdlg.TContentHTML, { extends: 'div' });
customElements.define('svs-content-web', svsdlg.TContentWeb, { extends: 'div' });
customElements.define('svs-content-frame', svsdlg.TContentIFrame, { extends: 'div' });
var svsdlg;
(function (svsdlg) {
    class TPopup extends HTMLDivElement {
        constructor(options, defs) {
            super();
            let _defs = clone(defs);
            extend(_defs, options);
            Object.assign(this, _defs);
            this.render();
        }
        set body(val) {
            this._content = new val.class(val);
        }
        set cssClass(val) {
            this.classList.add.apply(this.classList, val);
        }
        render() {
            this.append(this._content);
        }
        show() {
            this.classList.add('show');
        }
        hide() {
            this.classList.remove('show');
        }
    }
    svsdlg.TPopup = TPopup;
})(svsdlg || (svsdlg = {}));
customElements.define('svs-popup', svsdlg.TPopup, { extends: 'div' });
var svsdlg;
(function (svsdlg) {
    class TDialog extends svsdlg.TPopup {
        constructor(options, defs) {
            super(options, defs);
            this.modal = false;
            this.closeOnClickOutside = false;
            if (!options) {
                options = svsdlg.defDialog;
            }
        }
        set lever(val) {
            this._lever = new svsdlg.TDialogLever(val);
        }
        get lever() {
            return this._lever;
        }
        set caption(val) {
            this.lever.caption = val;
        }
        render() {
            this._lever ? this.appendChild(this._lever) : null;
            this._content ? this.appendChild(this._content) : null;
            this._buttons ? this.appendChild(this._buttons) : null;
        }
    }
    svsdlg.TDialog = TDialog;
    class TDialogLever extends HTMLDivElement {
        constructor(options) {
            super();
            Object.assign(this, options);
            this.render();
        }
        set caption(val) {
            if (!this._caption) {
                this._caption = document.createElement('span');
                this._caption.classList.add('dlg-caption');
            }
            this._caption.textContent = val;
        }
        set captionIcon(val) {
            if (!this._icon) {
                this._icon = document.createElement('img');
            }
            if (typeof val == 'string') {
                this._icon.classList.add(val);
            }
        }
        get dialog() {
            return this.closestType(svsdlg.TPopup);
        }
        render() {
            this._icon ? this.append(this._icon) : null;
            this._caption ? this.append(this._caption) : null;
        }
    }
    svsdlg.TDialogLever = TDialogLever;
    class TDialogButtons extends HTMLDivElement {
    }
    svsdlg.TDialogButtons = TDialogButtons;
    class TDialogError extends TDialog {
        constructor(options) {
            super(options !== null && options !== void 0 ? options : svsdlg.defErrorDialog);
        }
    }
    svsdlg.TDialogError = TDialogError;
})(svsdlg || (svsdlg = {}));
customElements.define('svs-dialog', svsdlg.TDialog, { extends: 'div' });
customElements.define('svs-dialog-lever', svsdlg.TDialogLever, { extends: 'div' });
customElements.define('svs-dialog-buttons', svsdlg.TDialogButtons, { extends: 'div' });
customElements.define('svs-dialog-error', svsdlg.TDialogError, { extends: 'div' });
