tinymce.PluginManager.add("collections", function (editor, url) {
    //editor.ui.registry.addIcon('bubbles', '<svg width="24" height="24"><use xlink:href="custom-icons.svg#bubbles4"></use></svg>');
    /*
    Use to store the instance of the Dialog
     */
    var _dialog = false;
    /*
    An array of options to appear in the "Type" select box.
     */
    var _typeOptions = [];
    /**
     * Get the Dialog Configuration Object
     *
     * @returns {{buttons: *[], onSubmit: onSubmit, title: string, body: {}}}
     * @private
     */
    function _getDialogConfig()
    {
        return {
            title: 'Вставить список',
            body: {
                type: 'panel',
                items: [{
                    type: 'selectbox',
                    name: 'type',
                    label: 'Список',
                    items: _typeOptions,
                    flex: true
                },
                {
                    type: 'input',
                    name: 'limit',
                    label: 'Записей на страницу',
                    flex: true
                }
                ]
            },
            onSubmit: function (api) {
                // insert markup
                editor.insertContent('<collection data-id="'+api.getData().type+'" data-limit="'+api.getData().limit+'">Список #'+api.getData().type+'.</collection>');

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
                url: '/collection/get-collections',
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
    editor.ui.registry.addButton('collections', {
        text: "Списки",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('collections', {
        text: 'Списки',
        context: 'insert',
        onAction: _onAction
    });

    // Return details to be displayed in TinyMCE's "Help" plugin, if you use it
    // This is optional.
    return {
        getMetadata: function () {
            return {
                name: "Hello World example",
                url: "https://www.martyfriedel.com/blog/tinymce-5-creating-a-plugin-with-a-dialog-and-custom-icons"
            };
        }
    };
});

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