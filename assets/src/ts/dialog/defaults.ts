/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

module svsdlg {
    enum TDialogContentType {
        CT_HTML = 2,
        CT_IFRAME = 3,
        CT_WEB = 4
    }

    export enum TLeverButtons {
        LB_CLOSE = 0x1,
        LB_MAXIMIZE = 0x2,
        LB_MINIMIZE = 0x4,
    }

    export interface IDefaultSettings {
        cssClass?: string[];
        body?: IContentOptions;
        lever?: IDialogLeverOptions;
    }

    export let defDialog: IDefaultSettings = {
        cssClass: ['svs-dialog'],
        lever: {buttons: TLeverButtons.LB_CLOSE},
    }

    export let defErrorDialog: IDefaultSettings = {
        cssClass: ['svs-dialog', 'svs-dialog-error'],
    }
}