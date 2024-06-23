declare module svsdlg {
    enum TLeverButtons {
        LB_CLOSE = 1,
        LB_MAXIMIZE = 2,
        LB_MINIMIZE = 4
    }
    interface IDefaultSettings {
        cssClass?: string[];
        body?: IContentOptions;
        lever?: IDialogLeverOptions;
    }
    let defDialog: IDefaultSettings;
    let defErrorDialog: IDefaultSettings;
}
declare module svsdlg {
    enum TContentEvents {
        beforeContentLoad = "beforeContentLoad",
        afterContentLoad = "afterContentLoad",
        iframeLoaded = "iframeLoaded"
    }
    interface IContentConstructor {
        new (val: svsdlg.IContentOptions): svsdlg.TContent;
    }
    interface IContentOptions {
        class: IContentConstructor;
        cssClass?: string[];
        data?: string | string[] | HTMLElement | NodeListOf<HTMLElement>;
        arrayLinesSeparator?: string;
        url?: string;
        fetchMethod?: string;
    }
    class TContent extends HTMLDivElement {
        arrayLinesSeparator: string;
        constructor(options: IContentOptions);
        set cssClass(val: any);
        set data(val: string | string[] | HTMLElement | NodeListOf<HTMLElement>);
        protected clear(): void;
    }
    class TContentHTML extends TContent {
    }
    class TContentIFrame extends TContent {
        constructor(options: IContentOptions);
        connectedCallback(): void;
        get frame(): HTMLIFrameElement;
        get contentDocument(): Document;
        get contentWindow(): Window;
    }
    class TContentWeb extends TContent {
    }
}
declare module svsdlg {
    class TPopup extends HTMLDivElement {
        protected _content: TContent;
        constructor(options?: IDefaultSettings, defs?: IDefaultSettings);
        set body(val: IContentOptions);
        set cssClass(val: string[]);
        protected render(): void;
        show(): void;
        hide(): void;
    }
}
declare module svsdlg {
    class TDialog extends TPopup {
        protected _lever: TDialogLever;
        protected _buttons: TDialogButtons;
        modal: boolean;
        closeOnClickOutside: boolean;
        constructor(options?: IDefaultSettings, defs?: IDefaultSettings);
        set lever(val: IDialogLeverOptions);
        get lever(): IDialogLeverOptions;
        set caption(val: string);
        protected render(): void;
    }
    interface IDialogLeverOptions {
        caption?: string;
        captionIcon?: string | boolean;
        buttons?: number;
    }
    class TDialogLever extends HTMLDivElement {
        protected _icon: HTMLImageElement;
        protected _caption: HTMLSpanElement;
        constructor(options?: IDialogLeverOptions);
        set caption(val: string);
        set captionIcon(val: string | boolean);
        get dialog(): HTMLElement;
        render(): void;
    }
    class TDialogButtons extends HTMLDivElement {
    }
    class TDialogError extends TDialog {
        constructor(options?: IDefaultSettings);
    }
}
