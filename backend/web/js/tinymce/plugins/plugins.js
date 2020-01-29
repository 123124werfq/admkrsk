function getTinyContents(editor) {
    /** get iframe nodes */
    let contents = editor.getContainer();
    let iframe = contents.querySelector(".tox-editor-container .tox-sidebar-wrap .tox-edit-area #page-content_ifr");
    return  iframe.contentDocument.querySelector('html #tinymce').children;
}

(function() {
    var iframe = (function() {
        'use strict';

        tinymce.PluginManager.add("collections", function(editor, url) {

            var _api = false;

            var _urlDialogConfig = {
                title: 'Вставка списка',
                url: '/collection/redactor',
                buttons: [{
                    type: 'cancel',
                    name: 'cancel',
                    text: 'Закрыть'
                }],
                onAction: function(instance, trigger) {
                    editor.windowManager.alert('onAction is running.<br><br>You can code your own onAction handler within the plugin.');

                    // close the dialog
                    instance.close();
                },

                width: 1000,
                height: 600
            };

            function setEdit(collectionId, base64) {
                editor.windowManager.openUrl({
                    ..._urlDialogConfig,
                    url: '/collection/redactor?Collection[id_parent_collection]=' + collectionId + '&data=' + base64 + '&edit=1',
                });
            }

            function setEditableCollections(){
                for (let item of getTinyContents(editor)) {
                    let collection = item.querySelector('collection');
                    if (collection) {
                        let encodeData = collection.getAttribute('data-encodedata');
                        if (encodeData) {
                            let collectionId = collection.getAttribute('data-id');
                            /** edit Collection with double click */
                            item.ondblclick = function () {
                                setEdit(collectionId, encodeData);
                            };
                        }
                    }
                }
            }

            setTimeout(function () {
                setEditableCollections();
            }, 500);

            editor.addCommand('iframeCommand', function(ui, value) {

                if (value.id_collection == '') {
                    editor.windowManager.alert('Вы не выбрали список');
                }

                /** behaviour of edit collection */
                if (value.isEdit === true) {
                    for (let item of getTinyContents(editor)) {
                        let collection = item.querySelector('collection');
                        if (collection) {
                            let dataId = collection.getAttribute('data-id');
                            if (dataId == value.id_collection) {
                                collection.setAttribute('data-encodedata', value.base64);
                                collection.setAttribute('data-id', dataId);
                                item.ondblclick = function () {
                                    setEdit(dataId, value.base64);
                                };
                            }
                        }
                    }

                } else {
                    /** behaviour of create collection */
                    editor.insertContent('<p><collection data-id=' + value.id_collection + ' data-encodedata="' + value.base64 + '">Список #' + value.id_collection + '.</collection></p>');
                    setEditableCollections();
                }

                $(".tox-button--secondary").click();
            });

            // Define the Menu Item
            editor.ui.registry.addMenuItem('collections', {
                text: 'Списки',
                context: 'insert',
                onAction: () => {
                    _api = editor.windowManager.openUrl(_urlDialogConfig)
                }
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

/** Set on double click feature for edit plugin */
function setElementsEditable(editor, selector, editFunc) {
    for (let item of getTinyContents(editor)) {
        let element = item.querySelector(selector);
        if (element) {
            element.ondblclick = function () {
                editFunc(element);
            }
        }
    }
}

tinymce.PluginManager.add("form", function(editor, url) {
    var _dialog = false;
    var _forms = [];

    setTimeout(function () {
        setElementsEditable(editor, 'forms', editableForm);
    }, 100);

    let editDialog = {
        title: 'Изменить форму',
        body: {},
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

    function editableForm(form) {
        let formId = form.getAttribute('data-id');
        $.ajax({
            url: '/form/get-form?form-id=' + formId,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                editDialog.body = {
                    type: 'panel',
                    items: [{
                        type: 'selectbox',
                        name: 'id_form',
                        label: 'Форма',
                        items: data,
                        flex: true
                    }]
                };
                editDialog.onSubmit = function (api) {
                    let editFormId = api.getData().id_form;
                    form.setAttribute('data-id', editFormId);
                    form.innerText = 'Форма #' + editFormId + '.';
                    form.ondblclick = function () {
                        editableForm(form)
                    };
                    api.close();
                };
                _dialog = editor.windowManager.open(editDialog);
                _dialog.block('Loading...');
                _dialog.redial(editDialog);
                _dialog.unblock();
            }
        });
    }

    function _getDialogConfig() {
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
                }, ]
            },
            onSubmit: function(api) {
                // insert markup
                editor.insertContent('<forms data-id="' + api.getData().id_form + '">Форма #' + api.getData().id_form + '.</forms>');

                setElementsEditable(editor, 'forms', editableForm);
                // close the dialog
                api.close();
            },
            buttons: [{
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
    function _onAction() {
        // Open a Dialog, and update the dialog instance var
        _dialog = editor.windowManager.open(_getDialogConfig());

        // block the Dialog, and commence the data update
        // Message is used for accessibility
        _dialog.block('Loading...');

        // Do a server call to get the items for the select box
        // We'll pretend using a setTimeout call
        setTimeout(function() {

            // We're assuming this is what runs after the server call is performed
            // We'd probably need to loop through a response from the server, and update
            // the _forms array with new values. We're just going to hard code
            // those for now.
            _forms = [];

            $.ajax({
                url: '/form/get-form',
                type: 'get',
                dataType: 'json',
                //data: {_csrf: csrf_value},
                success: function(data) {
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

tinymce.PluginManager.add("pagenews", function(editor, url) {
    var _dialog = false;
    var _forms = [];

    setTimeout(function () {
        setElementsEditable(editor, 'pagenews', editablePage);
    }, 100);

    let editDialog = {
        title: 'Изменить новости',
        body: {},
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

    function editablePage(page) {
        let pageId = page.getAttribute('data-id');
        $.ajax({
            url: '/page/get-page?news=1' + '&page-id=' + pageId,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                editDialog.body = {
                    type: 'panel',
                    items: [{
                        type: 'selectbox',
                        name: 'id_page',
                        label: 'Новости',
                        items: data,
                        flex: true
                    }]
                };
                editDialog.onSubmit = function (api) {
                    let editPageId = api.getData().id_page;
                    page.setAttribute('data-id', editPageId);
                    page.innerText = 'Новости #' + editPageId + '.';
                    page.ondblclick = function () {
                        editablePage(page)
                    };
                    api.close();
                };
                _dialog = editor.windowManager.open(editDialog);
                _dialog.block('Loading...');
                _dialog.redial(editDialog);
                _dialog.unblock();
            }
        });
    }

    function _getDialogConfig() {
        return {
            title: 'Вставить новости',
            body: {
                type: 'panel',
                items: [{
                    type: 'selectbox',
                    name: 'id_page',
                    label: 'Раздел новостей',
                    items: _forms,
                    flex: true
                },]
            },
            onSubmit: function(api) {
                // insert markup
                editor.insertContent('<pagenews data-id="' + api.getData().id_page + '">Новости #' + api.getData().id_page + '.</pagenews>');

                setElementsEditable(editor, 'pagenews', editablePage);
                // close the dialog
                api.close();
            },
            buttons: [{
                    text: 'Закрыть',
                    type: 'cancel',
                    onclick: 'close'
                },
                {
                    text: 'Вставить',
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
    function _onAction() {
        // Open a Dialog, and update the dialog instance var
        _dialog = editor.windowManager.open(_getDialogConfig());

        // block the Dialog, and commence the data update
        // Message is used for accessibility
        _dialog.block('Loading...');

        // Do a server call to get the items for the select box
        // We'll pretend using a setTimeout call
        setTimeout(function() {

            // We're assuming this is what runs after the server call is performed
            // We'd probably need to loop through a response from the server, and update
            // the _forms array with new values. We're just going to hard code
            // those for now.
            _forms = [];

            $.ajax({
                url: '/page/get-page?news=1',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    _forms = data;
                    _dialog.redial(_getDialogConfig());
                    // unblock the dialog
                    _dialog.unblock();
                }
            });
        }, 100);
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('pagenews', {
        text: "Новости",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('pagenews', {
        text: 'Новости',
        context: 'insert',
        //icon: 'bubbles',
        onAction: _onAction
    });
});


tinymce.PluginManager.add("gallery", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    setTimeout(function () {
        setElementsEditable(editor, 'gallery', editableGallery);
    }, 100);

    let editDialog = {
        title: 'Изменить галерею',
        body: {},
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

    function editableGallery(gallery) {
        let galleryId = gallery.getAttribute('data-id');
        let galleryLimitText = gallery.getAttribute('data-limit');
        $.ajax({
            url: '/gallery/get-gallery?' + '&gallery-id=' + galleryId,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                editDialog.initialData = {
                    /** default limit value */
                    limit: galleryLimitText
                };
                editDialog.body = {
                    type: 'panel',
                    items: [
                        {
                            type: 'selectbox',
                            name: 'id_gallery',
                            label: 'Галлерея',
                            items: data,
                            flex: true
                        },
                        {
                            type: 'input',
                            name: 'limit',
                            label: 'Видимых записей',
                            flex: true
                        }
                    ]
                };
                editDialog.onSubmit = function (api) {
                    let editGalleryId = api.getData().id_gallery;
                    let editGalleryLimit = api.getData().limit;
                    gallery.setAttribute('data-id', editGalleryId);
                    gallery.setAttribute('data-limit', editGalleryLimit);
                    gallery.innerText = 'Галлерея #' + editGalleryId + '.';
                    gallery.ondblclick = function () {
                        editableGallery(gallery)
                    };
                    api.close();
                };
                _dialog = editor.windowManager.open(editDialog);
                _dialog.block('Loading...');
                _dialog.redial(editDialog);
                _dialog.unblock();
            }
        });
    }

    function _getDialogConfig() {
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
            onSubmit: function(api) {
                // insert markup
                editor.insertContent('<gallery data-id="' + api.getData().type + '" data-limit="' + api.getData().limit + '">Галерея #' + api.getData().type + '.</gallery>');

                setElementsEditable(editor, 'gallery', editableGallery);

                // close the dialog
                api.close();
            },
            buttons: [{
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
    function _onAction() {
        // Open a Dialog, and update the dialog instance var
        _dialog = editor.windowManager.open(_getDialogConfig());

        // block the Dialog, and commence the data update
        // Message is used for accessibility
        _dialog.block('Loading...');

        // Do a server call to get the items for the select box
        // We'll pretend using a setTimeout call
        setTimeout(function() {

            // We're assuming this is what runs after the server call is performed
            // We'd probably need to loop through a response from the server, and update
            // the _typeOptions array with new values. We're just going to hard code
            // those for now.
            _typeOptions = [];

            $.ajax({
                url: '/gallery/get-gallery',
                type: 'get',
                dataType: 'json',
                //data: {_csrf: csrf_value},
                success: function(data) {
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

tinymce.PluginManager.add("hrreserve", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    /**
     * Plugin behaviour for when the Toolbar or Menu item is selected
     *
     * @private
     */
    function _onAction() {
        editor.insertContent('<hrreserve pagesize="50">Кадровый Резерв</hrreserve>');
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('hrreserve', {
        text: "Кадровый резерв",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('hrreserve', {
        text: 'Кадровый резерв',
        context: 'insert',
        //icon: 'bubbles',
        onAction: _onAction
    });
});