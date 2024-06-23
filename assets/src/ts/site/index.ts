/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

document.addEventListener('DOMContentLoaded', () => {
    try {
        document.querySelector('.dlg1').addEventListener('click', () => {
            let dlg = new svsdlg.TDialog(<svsdlg.IDefaultSettings>{
                    body: {
                        class: svsdlg.TContentIFrame,
                        cssClass: ['mdfh'],
                        url: 'http://ast.test'
                    },
                    lever: {
                        caption: 'Дядя Вася был голодный, проглотил утюг холодный'
                    }
                },
                svsdlg.defDialog
            );
            document.body.append(dlg);
        });
    } catch (err) {
    }
});