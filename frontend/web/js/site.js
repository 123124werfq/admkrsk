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

$(document).ready(function() {

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

    if ($('.damask-form').length)
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


        $("#datepicker").datepicker({
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

        if(originalLink.indexOf('://')==-1) originalLink = location.origin + originalLink;

        let previewLink = "https://docs.google.com/gview?embedded=false&url="+originalLink;

        if(!$(el).hasClass('btn'))
            $(el).attr('href', previewLink).attr('target', '_blank');
    });

    $('#gosbar-search-go, #gosbar-search-go-btn').click(function(){
        if($('.header-search').val()!='')
            $('#top-search').submit();
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