/*
 Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
 */
        CKEDITOR.dialog.add('templates', function (editor) {
            return {
                title: 'Creater Bootsrap Template',
                minWidth: 400,
                minHeight: 200,
                contents: [
                    {
                        id: 'tab-basic',
                        label: 'Basic Settings',
                        elements: [
                            {
                                type: 'html',
                                html: '<div id="results">AAAA</div>'
                            }
                        ]
                    }
                ],
                onOk: function () {
                    var dialog = this;
                    var sections = parseInt(dialog.getValueOf('tab-basic', 'number')); //Número de seções que serão criadas
                    intern = ""
                    for (i = 1; i <= sections; i++) {
                        intern = intern + '<h3>BAŞLIK ' + i + '</h3><div><p>İçerik ' + i + '</p></div>'
                    }

                    editor.insertHtml('<div class="accordion">' + intern + '</div>');
                    $(".accordion").accordion();

                }
            };
        });

