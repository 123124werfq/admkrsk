$('#sb').click(function (event) {
    getUserSettings();
});

setTimeout(() => {

    $('.datepicker').each(function () {
        $(this).dateRangePicker({
            language: 'ru',
            format: 'YYYY-MM-DD',
            separator: ' до ',
        });
    });
}, 50);


$(function () {
    $("#accordion").accordion({
        collapsible: true,
        active: false,
        animate: 0
    });
});

function getUserSettings() {
    let elems = document.getElementsByClassName('ui-state-default');
    let data = [];
    for (let i = 0; i < elems.length; i++) {
        data.push(
            {
                position: elems[i].offsetLeft,
                columnName: elems[i].innerText.trim(),
                isVisible: elems[i].children[0].checked
            }
        );
    }
    const metaElement = document.querySelector('meta[name=csrf-token]').getAttribute('content');
    let formData = new FormData();
    formData.append('_csrf-backend', metaElement);
    formData.append('json', JSON.stringify(data));
    formData.append('class', $('#grid').data('grid'));

    fetch('/grid/save-grid-settings', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (response.status === 200) {
                location.reload();
            }
        });
}

$(function () {
    $("#sortable").sortable({});
    $("ul, li").disableSelection();
});