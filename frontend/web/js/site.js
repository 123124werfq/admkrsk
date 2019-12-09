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


function addInput(block)
{
    // находим сколько строк
    var count = $("#"+block+">div,#"+block+">tr").length;
    count++;

    // клонируем первую строку
    var input_row = $("#"+block+">div:eq(0),#"+block+">tr:eq(0)").clone();
    input_row.find('.tox-tinymce').remove();

    // добавляем в конец блока и меням ID у input
    input_row.appendTo("#"+block);

    input_row.find("input, select, textarea").each(function(i)
    {
        var clone_input = $(this);
        //id = parseInt(id.replace(/\D+/g,""),10);
        var input_id    = clone_input.attr("id");
        var input_name  = clone_input.attr("name");

        if (input_id!=undefined)
        {
            input_id = input_id.substr(0,input_id.indexOf('0'));
            input_name = input_name.substr(0,input_name.indexOf('0'))+count+input_name.substr(input_name.indexOf('0')+1);

            clone_input.attr('id',input_id+count).attr('name',input_name).val('');
        }
    });

    return false;
}


$(document).ready(function() {

    $("#news-rubric, #news-date").change(function(){
        $("#news-filter").submit();
    });

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

    if (typeof visibleInputs !== 'undefined')
    {
        function getValue(id_input)
        {
            var input = $("#input"+id_input);
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
                $("#element"+id_element).show();
            else
                $("#element"+id_element).hide();
        }

        for (var id_vinput in visibleInputs)
        {
            var source = $("#input"+id_vinput);

            source.change(function(){
                for (var id_element in visibleInputs[id_vinput])
                    check(id_element);
            });
        }

        for (var id_element in visibleElements)
            check(id_element);
    }

    $("div[data-visible-field]").each(function(){

        var source = $("#"+$(this).data('visible-field'));

        var block = $(this);

        function check()
        {
            var values = block.data('values').split(',');

            if (source.is(':checkbox'))
            {
                if (source.is(':checked'))
                    var val = source.val();
                else
                    var val = null
            }
            else
                var val = source.val();

            if (values.indexOf(val)<0)
              block.hide();
            else
              block.show();
        }

        if (source.length>0)
        {
          source.change(function(){
            check();
          });

          check();
        }
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
        let previewLink = "https://docs.google.com/gview?embedded=true&url="+originalLink;
        //$(el).append('<a href="'+previewLink+'" traget="_blank">просмотр</a>');
        $(el).attr('href', previewLink).attr('target', '_blank');
    });

    $('#gosbar-search-go').click(function(){
        if($('.header-search').val()!='')
            $('#top-search').submit();
    });


    $(".fileupload").each(function(){
        var id_input = $(this).data('input');
        var new_index = 0;
        var maxFiles = $(this).data('maxfiles');
        var acceptedFiles = $(this).data('acceptedfiles');

        if (!maxFiles)
            maxFiles = null;

         if (!acceptedFiles)
            acceptedFiles = null;

        $(this).addClass('input'+id_input);

        var uploader = $(this).find('.fileupload_dropzone').dropzone({
            addRemoveLinks: true,
            url: "/media/upload",
            dictRemoveFile: '×',
            dictCancelUpload: '×',
            maxFiles:maxFiles,
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
                                    </div>\
                                </div>\
                            </div>',
            init: function(){
                this.on("success", function(file, response){
                    response = JSON.parse(response);
                    $(file.previewElement).append(
                        '<input type="hidden" name="FormDynamic[input'+id_input+']['+new_index+'][file_path]" value="'+response.file+'"/>'
                    );
                    $(file.previewElement).append(
                        '<input type="hidden" name="FormDynamic[input'+id_input+']['+new_index+'][filename]" value="'+response.filename+'"/>'
                    );
                    if ($(file.previewElement).find('.fileupload_preview-type img').attr('src')==undefined)
                        $(file.previewElement).find('.fileupload_preview-type').text(response.file.split('.').pop());

                    new_index++;
                });
            }
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


})