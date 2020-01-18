(function () {
    var iframe = (function () {
        'use strict';

        tinymce.PluginManager.add("collections", function (editor, url) {

            var _api = false;

            var _urlDialogConfig = {
                title: 'Вставка списка',
                url: '/collection/redactor',
                buttons: [
                    {
                        type: 'cancel',
                        name: 'cancel',
                        text: 'Закрыть'
                    }
                ],
                onAction: function (instance, trigger) {
                    editor.windowManager.alert('onAction is running.<br><br>You can code your own onAction handler within the plugin.');

                    // close the dialog
                    instance.close();
                },

                width: 1000,
                height: 600
            };

            editor.addCommand('iframeCommand', function (ui, value) {

                if (value.id_collection == '')
                    editor.windowManager.alert('Вы не выбрали список');
                else
                {
                    editor.insertContent('<collection \
                                            data-columns=\''+JSON.stringify(value)+'\' \
                                            data-id="'+value.id_collection+'" \
                                            data-template="'+value.template+'" \
                                            data-group="'+value.group+'" \
                                            data-pagesize="'+value.pagesize+'" \
                                            data-sort="'+value.sort+'" \
                                            data-show_on_map="'+value.show_on_map+'" \
                                            data-show_row_num="'+value.show_row_num+'" \
                                            data-show_column_num="'+value.show_column_num+'" \
                                            data-dir="'+value.dir+'">Список #'+value.id_collection+'.</collection>');
                }

                $(".tox-button--secondary").click();
            });

            // Define the Toolbar button
            editor.ui.registry.addButton('collections', {
                text: "Списки",
                onAction: () => {
                    _api = editor.windowManager.openUrl(_urlDialogConfig)
                }
            });
        });
    }());
})();

tinymce.PluginManager.add("form", function (editor, url) {
    var _dialog = false;
    var _forms = [];
    function _getDialogConfig()
    {
        return {
            title: 'Вставить форму',
            body: {
                type: 'panel',
                items: [{
                    type: 'selectbox',
                    name: 'id_form',
                    label: 'Форма',
                    items: _forms,
                    flex: true
                },
                ]
            },
            onSubmit: function (api) {
                // insert markup
                editor.insertContent('<forms data-id="'+api.getData().id_form+'">Форма #'+api.getData().id_form+'.</forms>');

                // close the dialog
                api.close();
            },
            buttons: [
                {
                    text: 'Close',
                    type: 'cancel',
                    onclick: 'close'
                },
                {
                    text: 'Insert',
                    type: 'submit',
                    primary: true,
                    enabled: false
                }
            ]
        };
    }

    /**
     * Plugin behaviour for when the Toolbar or Menu item is selected
     *
     * @private
     */
    function _onAction()
    {
        // Open a Dialog, and update the dialog instance var
        _dialog = editor.windowManager.open(_getDialogConfig());

        // block the Dialog, and commence the data update
        // Message is used for accessibility
        _dialog.block('Loading...');

        // Do a server call to get the items for the select box
        // We'll pretend using a setTimeout call
        setTimeout(function () {

            // We're assuming this is what runs after the server call is performed
            // We'd probably need to loop through a response from the server, and update
            // the _forms array with new values. We're just going to hard code
            // those for now.
            _forms = [
            ];

            $.ajax({
                url: '/form/get-form',
                type: 'get',
                dataType: 'json',
                //data: {_csrf: csrf_value},
                success: function(data)
                {
                  _forms = data;
                  _dialog.redial(_getDialogConfig());
                  // unblock the dialog
                  _dialog.unblock();
                }
            });


        }, 100);
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('form', {
        text: "Форма",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('form', {
        text: 'Форма',
        context: 'insert',
        //icon: 'bubbles',
        onAction: _onAction
    });
});


tinymce.PluginManager.add("gallery", function (editor, url) {
    var _dialog = false;
    var _typeOptions = [];
    function _getDialogConfig()
    {
        return {
            title: 'Вставить галерею',
            body: {
                type: 'panel',
                items: [{
                    type: 'selectbox',
                    name: 'type',
                    label: 'Галерея',
                    items: _typeOptions,
                    flex: true
                },
                {
                    type: 'input',
                    name: 'limit',
                    label: 'Видимых записей',
                    flex: true
                }
                ]
            },
            onSubmit: function (api) {
                // insert markup
                editor.insertContent('<gallery data-id="'+api.getData().type+'" data-limit="'+api.getData().limit+'">Галерея #'+api.getData().type+'.</gallery>');

                // close the dialog
                api.close();
            },
            buttons: [
                {
                    text: 'Close',
                    type: 'cancel',
                    onclick: 'close'
                },
                {
                    text: 'Insert',
                    type: 'submit',
                    primary: true,
                    enabled: false
                }
            ]
        };
    }

    /**
     * Plugin behaviour for when the Toolbar or Menu item is selected
     *
     * @private
     */
    function _onAction()
    {
        // Open a Dialog, and update the dialog instance var
        _dialog = editor.windowManager.open(_getDialogConfig());

        // block the Dialog, and commence the data update
        // Message is used for accessibility
        _dialog.block('Loading...');

        // Do a server call to get the items for the select box
        // We'll pretend using a setTimeout call
        setTimeout(function () {

            // We're assuming this is what runs after the server call is performed
            // We'd probably need to loop through a response from the server, and update
            // the _typeOptions array with new values. We're just going to hard code
            // those for now.
            _typeOptions = [
            ];

            $.ajax({
                url: '/gallery/get-gallery',
                type: 'get',
                dataType: 'json',
                //data: {_csrf: csrf_value},
                success: function(data)
                {
                  _typeOptions = data;
                  _dialog.redial(_getDialogConfig());
                  // unblock the dialog
                  _dialog.unblock();
                }
            });


        }, 100);
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('gallery', {
        text: "Галерея",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('gallery', {
        text: 'Галерея',
        context: 'insert',
        //icon: 'bubbles',
        onAction: _onAction
    });
});

tinymce.PluginManager.add("hrreserve", function (editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    /**
     * Plugin behaviour for when the Toolbar or Menu item is selected
     *
     * @private
     */
    function _onAction()
    {
        editor.insertContent('<hrreserve>Кадровый Резерв</hrreserve>');
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('hrreserve', {
        text: "Резерв",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('hrreserve', {
        text: 'Резерв',
        context: 'insert',
        //icon: 'bubbles',
        onAction: _onAction
    });
});