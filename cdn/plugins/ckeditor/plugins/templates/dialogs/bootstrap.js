/*
 Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
 */

        CKEDITOR.dialog.add('templates', function (editor) {
            return {
                title: 'Creater Bootstrap Template',
//        minWidth: 400,
//        minHeight: 100,
                contents: [
                    {
                        id: 'tab-basic',
                        label: 'Basic Settings',
                        elements: [
                            {
                                type: 'html',
                                html: '<div id="results"><iframe id="creater-bootstrap-html" src="' + CKEDITOR.plugins.getPath('templates') + '/index.html"  style="height: 80vh; width: 90vw;"></iframe></div>'
                            }
                        ]
                    }
                ],
                onOk: function () {
                    editor.insertHtml(document.getElementById("creater-bootstrap-html").contentWindow.downloadHtmlLayout());

                }
            };
        });

