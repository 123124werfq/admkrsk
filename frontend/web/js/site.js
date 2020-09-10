let cdtimer = 3600, intervalcd;

function removeRow(obj)
{
  var parent = $(obj).parent().parent();

  if (parent.siblings().length>0)
      parent.remove();
  else
      parent.find("input,textarea").val('');

  return false;
}

function getValueById(id)
{
    if ($("#"+id).length>0)
        return $("#"+id).val();

    return '';
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

function addInput(block)
{
    // находим сколько строк
    var count = $("#"+block+">div,#"+block+">tr").length;
    count++;

    // клонируем первую строку
    var input_row = $("#"+block+">div:eq(-1),#"+block+">tr:eq(-1)").clone();
    var data_row = input_row.data('row');
    input_row.find('.tox-tinymce').remove();
    input_row.attr('data-row',++data_row);

    // добавляем в конец блока и меням ID у input
    input_row.appendTo("#"+block);

    input_row.find("input, select, textarea").each(function(i)
    {
        var clone_input = $(this);

        var input_id    = clone_input.attr("id");
        var input_name  = clone_input.attr("name");

        if (input_id!=undefined)
        {
            //input_id = input_id.substr(0,input_id.indexOf('0'));
            input_name = input_name.replace('['+(data_row-1)+']','['+data_row+']');// input_name.substr(0,input_name.indexOf('0'))+count+input_name.substr(input_name.indexOf('0')+1);

            clone_input.attr('id',input_id+data_row).attr('name',input_name).val('');
        }
    });

    return false;
}

function recalculateFormSize(form)
{
    var maxtotalsize = form.data('maxfilesize');

    var currentSize = 0;
    form.find('.fileupload_item').each(function(){
        currentSize += $(this).data('filesize')*1;
    });

    console.log(maxtotalsize+ ' < '+currentSize);

    $(".currentFormSize").html(' вы загрузили '+(currentSize/(1024*1024)).toFixed(2)+'Мб');

    return currentSize;
}

function visibleForm(visibleInputs,visibleElements,dom)
{
    var $dom = $(dom);

    function getValue(id_input)
    {
        var input = $dom.find("#formdynamic-input"+id_input+", .formdynamic-input"+id_input+" input[name*='input"+id_input+"']:checked");

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
            $dom.find("#element"+id_element).show().find('input, textarea, select').prop('disabled',false);
        else
            $dom.find("#element"+id_element).hide().find('input, textarea, select').prop('disabled',true);
    }

    for (var id_vinput in visibleInputs)
    {
        $dom.find("#formdynamic-input"+id_vinput+", .formdynamic-input"+id_vinput+" input[name*='input"+id_vinput+"']").on("change changeaccept",function(){

            if ($(this).data('id'))
                var id = $(this).data('id');
            else
                var id = $(this).attr('id').replace('formdynamic-input','');

            for (var id_element in visibleInputs[id])
                check(id_element);
        });
    }

    for (var id_element in visibleElements)
        check(id_element);
}

$(document).ready(function() {

    $(".repeat-switcher").change(function(){
        if ($(this).prop('checked'))
            $(this).closest('.flex-wrap').find('.is_repeat').show();
        else
            $(this).closest('.flex-wrap').find('.is_repeat').hide();
    });

    $(".repeat_repeat").change(function(){
        var $this = $(this);
        $this.closest('.flex-wrap').find('.repeat-block').hide();
        $this.closest('.flex-wrap').find('.repeat-block[data-repeat='+$this.val()+']').show();
    });

    $(".repeat_month").change(function(){
        var $this = $(this);
        console.log('.repeat-block-month[data-repeat="'+$this.val()+'"]');
        $this.closest('.flex-wrap').find('.repeat-block-month').hide();
        $this.closest('.flex-wrap').find('.repeat-block-month[data-repeat="'+$this.val()+'"]').show();
    });

    $(".ajax-form").on('beforeSubmit', function (event) {
        event.preventDefault();

        var $form = $(this);

        $form.find('.has-error .help-block').html('');
        $form.find('.has-error').removeClass('has-error');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: $form.attr('action'),
            data: $form.serialize()
        }).done(function(data){

            if (data.success)
                $form.html(data.success);
            else
            {
                $.each(data,function(index, value){
                    if ($("."+index+' .help-block').length>0)
                    {
                        $("."+index).addClass('has-error');
                        $("."+index+' .help-block').html(value.join('<br>'));
                    }
                });
            }
        });

        //e.preventDefault();
        //e.stopImmediatePropagation();
        return false;
    });

    var curpage = 0;

    $(".col-2-third .load-more").click(function(){

        curpage++;

        $.ajax({
            type: "GET",
            dataType: "html",
            url: "",
            data: {p:curpage}
        }).done(function(data){
            if (data=='')
                $(".col-2-third .load-more").hide();
            else
                $(".press-list").append(data);
        });

        return false;
    });

    if ($(".table-responsive").length>0)
    {
        $(".table-responsive").each(function(){
            var $table_wrapper = $(this);

            if (($table_wrapper.find('table').width()+200) > $table_wrapper.width())
            {
                $table_wrapper.closest('.widget-wrapper')
                    .prepend('<a href="javascript:" class="close-window">&times;</a>')
                    .find('.collection-controls')
                    .append('<a class="fullsize-table" href="javascript:">На весь экран</a>');
            }
        });
    }

    $(".copydate").change(function(){

        var checkbox = $(this);
        if (checkbox.prop('checked'))
        {
            var group = checkbox.closest(".form-group");

            $("#inputGroup"+checkbox.data('input')).find('input, select, textarea').each(function(){
                var input = $("#"+$(this).attr('id').replace(checkbox.data('input'),checkbox.val()));

                if (input.hasClass('select2-hidden-accessible'))
                {
                    input.html($(this).html());
                    input.val($(this).val());
                    input.trigger("change");
                }
                else
                    input.val($(this).val());
            });
        }
    });

    $("body").delegate('.showonmap','click',function(){
        if($('#map'+$(this).data('hash')+':visible').length)
            $('#map'+$(this).data('hash')+':visible').parent().removeClass('open');
        else
            showMap($(this).data('id'),'map'+$(this).data('hash'));
    });

    $("body").delegate('.fullsize-table','click',function(){
        var $link = $(this);

        $link.closest('.widget-wrapper').addClass('full-screen');
        if((typeof map != 'undefined') && (map)) map.container.fitToViewport();
        return false;
    });

    $(".widget-wrapper").delegate('.close-window','click',function(){
        $(this).parent().removeClass('full-screen');
        if((typeof map != 'undefined') && (map)) map.container.fitToViewport();
        return false;
    });

    $(".search-table select, .search-table input").on('change datepicker-change',function(){
        var $form = $(this).closest('form');
        $.pjax({
            container: '#'+$form.data('hash'),
            data: $form.serialize(),
            timeout:10000,
            scrollTo:false,
        });
    });

    $("#Complaint_id_firm").change(function(){
        $.ajax({
            type: "GET",
            dataType: "html",
            url: "/form/get-categories",
            data: {id:$("#Complaint_id_firm").val()}
        }).done(function(data){
            $("#Complaint_id_category").html(data).selectmenu("refresh");
        });
    });

    $("#news-rubric, #news-date").change(function(){
        $("#news-filter").submit();
    });

    $('.boxed.form-inside').delegate(".delete-subform",'click',function(){
        if ($(this).closest('.subform').parent().find('.subform').length>1)
            $(this).closest('.subform').remove();
        return false;
    });

    $('body').delegate(".accept-checkbox",'click',function(){

        var inputID = $(this).data('id');
        $("#formdynamic-"+inputID).prop('checked','true').trigger('changeaccept');
        $.fancybox.close();

        return false;
    });

    $(".searchable img[data-full]").each(function(i){
        var link = $(this);

        console.log(link.data('full'));

        link.wrap($('<a href="'+link.data('full')+'" class="fancybox" data-fancybox="gallery-content" />'));
    });

    $(".modal-checkbox").change(function(){

        if ($(this).is(':checked'))
        {
            $(this).prop('checked',false);

            var modal = $(this).data('modal');

            $.fancybox.open({
                src: '#'+modal,
                modal: true
            });
        }
    });

    $(".form-copy").click(function(){
        var $link = $(this);

        $.ajax({
            type: "GET",
            dataType: "html",
            url: "/form/form-collection",
            data: {id:$link.data('id')}
        }).done(function(data){
            $("#"+$link.data('group')).append(data);
        });

        return false;
    })

    $("#news-date").on('datepicker-change', function(event,obj) {
        $("#news-filter").submit();
    });

    $("#reestr-filters select").change(function(){
        $.ajax({
            type: "GET",
            dataType: "html",
            url: "",
            data: $("#reestr-filters").serialize()
        }).done(function(data){
            $("#reestr").html(data);
        });
    });

    $("#program-filter input").on('datepicker-change', function(event,obj) {
        $.ajax({
            type: "GET",
            dataType: "html",
            url: "",
            data: $("#program-filter").serialize()
        }).done(function(data) {
            $(".program-list").html(data);
        });
    });

    $("#program-filter select, #program-filter input").change(function(){
        $.ajax({
            type: "GET",
            dataType: "html",
            url: "",
            data: $("#program-filter").serialize()
        }).done(function(data) {
            $(".program-list").html(data);
        });
    });

    $("#service_search_input").autocomplete({
        'minLength':'2',
        'showAnim':'fold',
        'select': function(event, ui) {
            document.location = ui.item['redirect'];
        },
        'change': function (event, ui) {
            if (ui.item)
            {

            }
            else if (obj.hasClass('strict')) {
                obj.val('');
                rinput.val('');
            }
        },
        'source':'/service/search'
    });

    if($('.damask-form').length)
    {
        var groupNum = -1, serviceNum = -1, avialableDays = [];

        $('.wrap_service_0').removeClass('hidden');
        $('body').on('click', '.ui-menu-item', function (e) {
            let currentGroup = $('select[name="type"]').val();

            if($('.wrap_service_'+currentGroup).hasClass('hidden'))
            {
                $('.wrap_service').addClass('hidden');
                $('.wrap_service_'+currentGroup).removeClass('hidden');
                $('#datepicker').val('');
            }

            let currentService = $('select[name="service_'+currentGroup+'"]').val();

            if(serviceNum != currentService)
            {
                $('#datepicker').val('');
                serviceNum = currentService;
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/book/available",
                    data: {service: currentService, type: currentGroup}
                }).done(function( data ) {
                    //location.reload();
                    avialableDays = data;
                    $( "#datepicker" ).datepicker('refresh');
                });
            }


        });

        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: 'Пред',
            nextText: 'След',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                'Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Нед',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['ru']);


        $( "#datepicker" ).datepicker({
            firstDay: 1,
            minDate: 0,
            changeMonth: true,
            changeYear: false,
            beforeShowDay: function (date) {

                for (let j=0; j<avialableDays.length; j++)
                {
                    let pdata = avialableDays[j].split("-");
                    if( (parseInt(pdata[2]) == date.getDate()) && ((parseInt(pdata[1]) == date.getMonth()+1) || (date.getMonth() == 0)) )
                    {
                        return [ true , "", ""];
                    }
                }

                return [ false , "day-inactive", ""];
            },
            onSelect: function (dateText, inst) {
                let currentGroup = $('select[name="type"]').val();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/book/intervals",
                    data: {datetext: dateText, service: serviceNum,  type: currentGroup}
                }).done(function( data ) {
                    if(data.length)
                    {
                        $('[name=time]').find('option').remove();
                        for (let i=0; i<data.length; i++)
                            $('[name=time]').append("<option value='"+data[i]+"'>"+ String(parseInt(data[i]/60)).padStart(2, '0') +":"+ String(parseInt(data[i]%60)).padStart(2, '0') +"</option>");
                        $('[name=time]').selectmenu('refresh');
                    }
                });
            }
        });

        $('select[name="service_0"]').on('loaded.bs.select', function () {
            var Selectpicker = $('#number').data('selectpicker');
        });

    }

    $('a[href$=".doc"], a[href$=".docx"], a[href$=".xls"], a[href$=".xlsx"]').each(function(idx,el){
        let originalLink = $(el).attr('href');

        //originalLink = originalLink.replace('administration', '_administration');

        if(originalLink.indexOf('://')==-1) originalLink = location.origin + originalLink;

        let previewLink = "https://docs.google.com/gview?embedded=false&url="+originalLink;
        //$(el).append('<a href="'+previewLink+'" traget="_blank">просмотр</a>');
        if(!$(el).hasClass('btn'))
            $(el).attr('href', previewLink).attr('target', '_blank');
    });

    $('#gosbar-search-go, #gosbar-search-go-btn').click(function(){
        if($('.header-search').val()!='')
            $('#top-search').submit();
    });

    $(".dz-remove").click(function(){
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
            /*accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                  done("Naha, you don't.");
                }
                else { done(); }
              }*/
        });
    });

    if((location.href.indexOf('administration/service')>0) && ($('.form-inside').length))
    {
        $('.form-inside').before("<div class=countdown></div>");
        intervalcd = setInterval(function() {
            cdtimer--;

            if(cdtimer<0)
            {
                clearInterval(intervalcd);
                alert('Сессия истекла. Необходимо повторно осуществить логин с помощью ЕСИА');
                $("[href='/site/logout']").click();
            }
            else
                $('.countdown').text((""+parseInt(cdtimer/60)).padStart(2, "0")+":"+(""+parseInt(cdtimer%60)).padStart(2, "0"))

        }, 1000);
    }

    $('#allPosOn').click(function(){
        $('select').each(function(idx,el){$(el).val(1);})
        return false;
    });
    $('#allPosOff').click(function(){
        $('select').each(function(idx,el){$(el).val(-1);})
        return false;
    });

})