function getFilter(settings,group)
{
    if (settings && settings.length>0)
    {
        if (group)
            group = '-'+group;
        else
            group = '';

        var filters = {};

        for (var i = settings.length - 1; i >= 0; i--)
            filters[settings[i].id_column] = $("#formdynamic"+group+"-input"+settings[i].id_input).val();

        return filters;
    }
    else
        return {};
}

function formCopy(element)
{
    var $link = $(element);

    $.ajax({
        type: "GET",
        dataType: "html",
        url: "/form/form-collection",
        data: {id:$link.data('id')}
    }).done(function(data){
        $("#"+$link.data('group')).append(data);
    });

    return false;
}

function selectPlace(id)
{
    var $input = $("#"+id);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: '/address/house-by-place',
        data: {id:$input.val()}
    }).done(function(data){
        if (data!={})
        {
            var container = $input.closest('.flex-wrap');

            for (var key in data)
            {
                console.log('select[name*="['+key+'"]');

                var select = container.find('select[name*="['+key+'"]');

                if (select.length>0)
                {

                    var newOption = new Option(data[key], data['id_'+key], true, true);
                    select.append(newOption).trigger('change');
                }
            }
        }
    });
}

function recalculateFormSize(form)
{
    var maxtotalsize = form.data('maxfilesize');

    var currentSize = 0;
    form.find('.fileupload_item').each(function(){
        currentSize += $(this).data('filesize')*1;
    });

    $(".currentFormSize").html(' вы загрузили '+(currentSize/(1024*1024)).toFixed(2)+'Мб');

    return currentSize;
}

function visibleForm(visibleInputs,visibleElements,dom)
{
    console.log(dom);
    let $dom = $(dom);

    if (dom.indexOf('form')==-1)
        let input_prefix = 'formdynamic-'+(dom.substring(1))+'-input';
    else
        let input_prefix = 'formdynamic-input';

    function getValue(id_input)
    {
        var input = $dom.find("#"+input_prefix+id_input+", .formdynamic-input"+id_input+" input[name*='input"+id_input+"']:checked");

        if (input.is(':checkbox'))
        {
            if (input.is(':checked'))
                var val = input.val();
            else
                var val = null
        }
        else
            var val = input.val();

        return val;
    }

    function check(id_element)
    {
        var show = true;

        for (var id_input in visibleElements[id_element])
        {
            var value = getValue(id_input);

            if (visibleElements[id_element][id_input].indexOf(value)<0)
            {
                show = false;
                break;
            }
        }

        if (show)
            $dom.find("#element"+id_element).show().find('input:not([readonly=""]), textarea:not([readonly=""]), select:not([readonly=""])').prop('disabled',false);
        else
            $dom.find("#element"+id_element).hide().find('input, textarea, select').prop('disabled',true);
    }

    for (var id_vinput in visibleInputs)
    {
        $dom.find("#"+input_prefix+id_vinput+", .formdynamic-input"+id_vinput+" input[name*='input"+id_vinput+"']").on("change changeaccept",function(){

            if ($(this).data('id'))
                var id = $(this).data('id');
            else
                var id = $(this).attr('id').replace('formdynamic-input','');

            console.log(id);

            for (var id_element in visibleInputs[id])
                check(id_element);
        });
    }

    for (var id_element in visibleElements)
        check(id_element);
}

$(document).ready(function() {

    $('body').delegate(".copydate",'change',function(){

        var checkbox = $(this);
        if (checkbox.prop('checked'))
        {
            console.log(123);

            var group = checkbox.closest(".form-group");

            $("#inputGroup"+checkbox.data('input')).find('input, select, textarea').each(function(){
                var input = $("#"+$(this).attr('id').replace(checkbox.data('input'),checkbox.val()));

                if (input.hasClass('select2-hidden-accessible'))
                {
                    console.log(111);
                    input.html($(this).html());
                    input.val($(this).val());
                    input.trigger("change");
                }
                else
                    input.val($(this).val());
            });
        }
    });

    $('body').delegate(".delete-subform",'click',function(){
        if ($(this).closest('.subform').parent().find('.subform').length>1)
            $(this).closest('.subform').remove();
        return false;
    });

    $("body").delegate(".repeat-switcher",'change',function(){
        if ($(this).prop('checked'))
            $(this).closest('.flex-wrap').find('.is_repeat').show();
        else
            $(this).closest('.flex-wrap').find('.is_repeat').hide();
    });

    $("body").delegate(".repeat_repeat",'change',function(){
        var $this = $(this);
        $this.closest('.flex-wrap').find('.repeat-block').hide();
        $this.closest('.flex-wrap').find('.repeat-block[data-repeat='+$this.val()+']').show();
    });

    $("body").delegate(".repeat_month",'change',function(){
        var $this = $(this);
        console.log('.repeat-block-month[data-repeat="'+$this.val()+'"]');
        $this.closest('.flex-wrap').find('.repeat-block-month').hide();
        $this.closest('.flex-wrap').find('.repeat-block-month[data-repeat="'+$this.val()+'"]').show();
    });

    $("body").delegate('.dz-remove','click',function(){
        var form = $(this).closest('form');
        $(this).closest('.fileupload_item').remove();
        recalculateFormSize(form);
        return false;
    });

    $(".fileupload").each(function(){
        var form = $(this).closest('form');
        var id_input = $(this).data('input');
        var new_index = $(this).find('.fileupload_item').length+1;
        var maxFiles = $(this).data('maxfiles');
        var maxFilesize = $(this).data('maxfilesize');
        var acceptedFiles = $(this).data('acceptedfiles');

        if (!maxFiles)
            maxFiles = null;

        if (!maxFilesize)
            maxFilesize = null;

        if (!acceptedFiles)
            acceptedFiles = null;

        $(this).addClass('input'+id_input);

        var uploader = $(this).find('.fileupload_dropzone').dropzone({
            addRemoveLinks: true,
            url: "/media/upload",
            dictRemoveFile: '×',
            dictCancelUpload: '×',
            maxFiles:maxFiles,
            maxFilesize:maxFilesize,
            acceptedFiles:acceptedFiles,
            resizeWidth: 1920,
            clickable: ".input"+id_input+" .fileupload_control",
            previewsContainer: ".input"+id_input+" .fileupload_list",
            previewTemplate: '<div class="fileupload_item">\
                                <div class="fileupload_preview">\
                                    <div class="fileupload_preview-type">\
                                        <img data-dz-thumbnail />\
                                    </div>\
                                </div>\
                                <div class="fileupload_item-content">\
                                    <p class="fileupload_item-name" data-dz-name></p>\
                                    <div class="fileupload_item-status"><span class="fileupload_item-size" data-dz-size></span>\
                                        <div class="fileupload_item-progress">\
                                            <div class="fileupload_progress-bar" data-dz-uploadprogress></div>\
                                        </div><div class="fileupload_item-progress-value">100%</div>\
                                        <div class="dz-error-message"><span data-dz-errormessage></span></div>\
                                    </div>\
                                </div>\
                                <div class="fileupload_item-pagecount">\
                                </div>\
                            </div>',
            dictFileTooBig:'Размер файла ({{filesize}}Mb). Максимальный размер: {{maxFilesize}}Mb.',
            init: function(){

                var plugin = this;
                this.on("success", function(file, response){
                    response = JSON.parse(response);

                    $(file.previewElement).append(
                        '<input type="hidden" name="FormDynamic[input'+id_input+']['+new_index+'][file_path]" value="'+response.file+'"/>'
                    );
                    $(file.previewElement).append(
                        '<input type="hidden" name="FormDynamic[input'+id_input+']['+new_index+'][filename]" value="'+response.filename+'"/>'
                    );
                    $(file.previewElement).find('.fileupload_item-pagecount').append(
                        'на <input class="form-control" type="number" step="1" min="1" name="FormDynamic[input'+id_input+']['+new_index+'][pagecount]" value="" placeholder="л."/> л.'
                    );

                    if ($(file.previewElement).find('.fileupload_preview-type img').attr('src')==undefined)
                        $(file.previewElement).find('.fileupload_preview-type').text(response.file.split('.').pop());

                    new_index++;
                });

                this.on("addedfile", function(file){

                    $(file.previewElement).attr('data-filesize',file.size);

                    var maxtotalsize = form.data('maxfilesize');
                    var currentSize = recalculateFormSize(form);

                    //console.log(maxtotalsize+ ' < '+currentSize);

                    if ((currentSize+file.size)>maxtotalsize)
                    {
                        plugin.removeFile(file);
                        alert('Вы превысили лимит максимального размера приложенных документов');
                        recalculateFormSize(form);
                    }
                });
            }
        });
    });
});