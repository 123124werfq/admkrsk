function getTinyContents(editor) {
    /** get iframe nodes */
    let contents = editor.getContainer();
    let iframe = contents.querySelector(".tox-editor-container .tox-sidebar-wrap .tox-edit-area iframe");
    return  iframe.contentDocument.querySelector('html #tinymce').children;
}

function getPageId() {
    let pageUrl = window.location.toString();
    return pageUrl.split('?id=')[1];
}

(function() {
    var iframe = (function() {
        'use strict';

        tinymce.PluginManager.add("collections", function(editor, url) {

            const CONTENT_ATTRIBUTE_NAME = 'data-encodedata';
            const KEY_ATTRIBUTE_NAME = 'data-key';

            var _api = false;

            var _urlDialogConfig = {
                title: 'Вставка списка',
                url: '/collection/redactor?page_id=' + getPageId(),
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

            function setEdit(collectionId, search) {
                editor.windowManager.openUrl({
                    ..._urlDialogConfig,
                    url: '/collection/redactor?Collection[id_parent_collection]=' + collectionId + '&key=' + search + '&edit=1&page_id=' + getPageId(),
                });
            }

            function setEditableCollections(){
                for (let item of getTinyContents(editor)) {
                    let collection = item.querySelector('collection');
                    if (collection) {
                        let key = collection.getAttribute(KEY_ATTRIBUTE_NAME);
                        if (key) {
                            let collectionId = collection.getAttribute('data-id');
                            /** edit Collection with double click */
                            item.ondblclick = function () {
                                setEdit(collectionId, key);
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
                            let key = collection.getAttribute(KEY_ATTRIBUTE_NAME);
                            if (key == value.key) {
                                let dataId = collection.getAttribute('data-id');
                                let encodeData = value.base64;
                                let key = value.key;
                                collection.setAttribute(CONTENT_ATTRIBUTE_NAME, encodeData);
                                collection.setAttribute(KEY_ATTRIBUTE_NAME, key);
                                item.ondblclick = function () {
                                    setEdit(dataId, key);
                                };
                            }
                        }
                    }

                } else {
                    /** behaviour of create collection */
                    editor.insertContent('<p><collection'+ ' '+ KEY_ATTRIBUTE_NAME + '=' + value.key +' data-id=' + value.id_collection + ' ' + CONTENT_ATTRIBUTE_NAME  + '="' + value.base64 + '">Список #' + value.id_collection + '.</collection></p>');
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

tinymce.PluginManager.add("ownmedia", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    function _onAction() {
        $.ajax({
            url: '/media/redactor',
            type: 'get',
            dataType: 'html',
            success: function(data) {
                $('#redactor-modal').modal();
                $('#redactor-modal .modal-body').html(data);
                $('#redactor-modal form').submit(function(){
                    $.ajax({
                        url: '/media/redactor',
                        type: 'post',
                        dataType: 'json',
                        data: $('#redactor-modal form').serialize(),
                        success: function(data) {

                            var full = '';

                            if (data.full != undefined)
                                full = 'data-full="'+data.full+'"';

                            editor.insertContent('<figure>\
                                        <img src="'+data.src+'" data-id="'+data.id_media+'" '+full+'\> \
                                        <figcaption class="img-legend">'+data.title+'</figcaption>\
                                    </figure>');
                            $('#redactor-modal').modal('hide');
                        }
                    });

                    return false;
                });
            }
        });
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('ownmedia', {
        //text: "Изображение",
        icon: 'image',
        onAction: _onAction
    });
});

tinymce.PluginManager.add("recordmap", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    function _onAction() {
        $.ajax({
            url: '/collection/redactor-map',
            type: 'get',
            dataType: 'html',
            success: function(data) {
                $('#redactor-modal').modal();
                $('#redactor-modal .modal-body').html(data);
                $('#redactor-modal form').submit(function(){
                    $.ajax({
                        url: '/form/redactor',
                        type: 'post',
                        dataType: 'json',
                        data: $('#redactor-modal form').serialize(),
                        success: function(data) {
                            editor.insertContent('<forms data-id="' + data.id_form + '">Форма #' + data.id_form + '.</forms>');
                            $('#redactor-modal').modal('hide');
                        }
                    });

                    return false;
                });
            }
        });
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('recordmap', {
        text: "Карта для записи",
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('recordmap', {
        text: 'Карта для записи',
        context: 'insert',
        onAction: _onAction
    });
});


tinymce.PluginManager.add("form", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    function _onAction() {
        $.ajax({
            url: '/form/redactor',
            type: 'get',
            dataType: 'html',
            success: function(data) {
                $('#redactor-modal').modal();
                $('#redactor-modal .modal-body').html(data);
                $('#redactor-modal form').submit(function(){
                    $.ajax({
                        url: '/form/redactor',
                        type: 'post',
                        dataType: 'json',
                        data: $('#redactor-modal form').serialize(),
                        success: function(data) {
                            editor.insertContent('<forms data-id="' + data.id_form + '">Форма #' + data.id_form + '.</forms>');
                            $('#redactor-modal').modal('hide');
                        }
                    });

                    return false;
                });
            }
        });
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('form', {
        text: "Форма",
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('form', {
        text: 'Форма',
        context: 'insert',
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
    let _galleryGroups = [];

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
        let galleryItem = gallery.getAttribute('data-id');
        let restUrl = '&gallery-id=' + galleryItem;
        if (!galleryItem) {
            galleryItem = gallery.getAttribute('data-groupId');
            restUrl = '&gallery-group-id=' + galleryItem;
        }
        let galleryLimitText = gallery.getAttribute('data-limit') || '';
        $.ajax({
            url: '/gallery/get-gallery?' + restUrl,
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
                            items: data.galleries,
                            flex: true
                        },
                        {
                            type: 'selectbox',
                            name: 'id_galleryGroup',
                            label: 'Группа галлерей',
                            items: data.galleriesGroup,
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
                    if (!editGalleryId) {
                        gallery.removeAttribute('data-id');
                        gallery.setAttribute('data-groupId', api.getData().id_galleryGroup);
                        gallery.setAttribute('data-limit', editGalleryLimit);
                        gallery.innerText = 'Группа галлерей #' + api.getData().id_galleryGroup + '.';
                    }
                    else {
                        gallery.setAttribute('data-id', editGalleryId);
                        gallery.removeAttribute('data-groupId');
                        gallery.setAttribute('data-limit', editGalleryLimit);
                        gallery.innerText = 'Галлерея #' + editGalleryId + '.';
                    }
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
                items: [
                    {
                        type: 'selectbox',
                        name: 'type',
                        label: 'Галерея',
                        items: _typeOptions,
                        flex: true
                    },
                    {
                        type: 'selectbox',
                        name: 'groupId',
                        label: 'Группы галлерей',
                        items: _galleryGroups,
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
                if (api.getData().groupId) {
                    editor.insertContent('<gallery data-groupId="' + api.getData().groupId + '">Группа галлерей #' + api.getData().groupId + '.</gallery>');
                    setElementsEditable(editor, 'gallery', editableGallery);
                }
                else {
                    editor.insertContent('<gallery data-id="' + api.getData().type + '" data-limit="' + api.getData().limit + '">Галерея #' + api.getData().type + '.</gallery>');
                    setElementsEditable(editor, 'gallery', editableGallery);
                }

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
            _galleryGroups = [];

            $.ajax({
                url: '/gallery/get-gallery',
                type: 'get',
                dataType: 'json',
                //data: {_csrf: csrf_value},
                success: function(data) {
                    _galleryGroups = data.galleriesGroup;
                    _typeOptions = data.galleries;
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

tinymce.PluginManager.add("recordSearch", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    function _onAction() {
        $.ajax({
            url: '/collection/record-search-redactor',
            type: 'get',
            dataType: 'html',
            success: function(data) {
                $('#redactor-modal').modal();
                $('#redactor-modal .modal-body').html(data);
                $('body').delegate('#redactor-modal form','submit',function(){
                    $.ajax({
                        url: '/collection/record-search-redactor',
                        type: 'post',
                        dataType: 'json',
                        data: $('#redactor-modal form').serialize(),
                        success: function(data) {
                            editor.insertContent('<p><searchrecord data-encodedata="'+data.attributes+'">Поиск по списку #' + data.id_collection + '.</searchrecord></p>');
                            $('#redactor-modal').modal('hide');
                        }
                    });

                    return false;
                });
            }
        });
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('recordSearch', {
        text: "Поиск по списку",
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('recordSearch', {
        text: 'Поиск по списку',
        context: 'insert',
        onAction: _onAction
    });
});

/*tinymce.PluginManager.add("faqcollection", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    function _onAction() {
        $.ajax({
            url: '/collection/redactor',
            type: 'get',
            data: {id_type:1},
            dataType: 'html',
            success: function(data) {
                $('#redactor-modal').modal();
                $('#redactor-modal .modal-body').html(data);
                $('#redactor-modal form').submit(function(){
                    $.ajax({
                        url: '/collection/redactor',
                        type: 'post',
                        dataType: 'json',
                        data: $('#redactor-modal form').serialize(),
                        success: function(data) {
                            editor.insertContent('<p><collection data-key='+data.key+' data-id='+data.id_collection+' ">Публичные слушания #' + data.id_collection + '.</collection></p>');
                            $('#redactor-modal').modal('hide');
                        }
                    });

                    return false;
                });
            }
        });
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('faqcollection', {
        text: "Публичные слушания",
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('faqcollection', {
        text: 'Публичные слушания',
        context: 'insert',
        onAction: _onAction
    });
});*/

tinymce.PluginManager.add("faq", function(editor, url) {
    var _dialog = false;
    var _typeOptions = [];

    function _onAction() {
        $.ajax({
            url: '/faq/redactor',
            type: 'get',
            dataType: 'html',
            success: function(data) {
                $('#redactor-modal').modal();
                $('#redactor-modal .modal-body').html(data);
                $('#redactor-modal form').submit(function(){
                    $.ajax({
                        url: '/faq/redactor',
                        type: 'post',
                        dataType: 'json',
                        data: $('#redactor-modal form').serialize(),
                        success: function(data) {
                            editor.insertContent('<p><faq data-id_faq_category="'+data.id_faq_category+'">Вопрос-Ответ #' + data.id_faq_category + '.</faq></p>');
                            $('#redactor-modal').modal('hide');
                        }
                    });

                    return false;
                });
            }
        });
    }

    // Define the Toolbar button
    editor.ui.registry.addButton('faq', {
        text: "Вопрос-Ответ",
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('faq', {
        text: 'Вопрос-Ответ',
        context: 'insert',
        onAction: _onAction
    });
});

tinymce.PluginManager.add("map", function(editor, url) {
    var _dialog = false;
    var _forms = [];

    setTimeout(function () {
        setElementsEditable(editor, 'maps', editableForm);
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
            title: 'Вставить карту',
            body: {
                type: 'panel',
                items: [{
                    type: 'selectbox',
                    name: 'id_collection',
                    label: 'Форма',
                    items: _forms,
                    flex: true
                }, ]
            },
            onSubmit: function(api) {
                // insert markup
                editor.insertContent('<map data-id="' + api.getData().id_collection + '">Карта списка #' + api.getData().id_collection + '.</map>');

                setElementsEditable(editor, 'map', editableForm);
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

        // We'll pretend using a setTimeout call
        setTimeout(function() {

            _forms = [];

            $.ajax({
                url: '/collection/get-collections',
                type: 'get',
                dataType: 'json',
                data: {map: 1},
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
    editor.ui.registry.addButton('map', {
        text: "Карта списка",
        //icon: 'bubbles',
        onAction: _onAction
    });

    // Define the Menu Item
    editor.ui.registry.addMenuItem('map', {
        text: 'Карта списка',
        context: 'insert',
        //icon: 'bubbles',
        onAction: _onAction
    });
});