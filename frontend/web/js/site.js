$(document).ready(function() {

    $("div[data-visible-field]").each(function(){

        var source = $("#"+$(this).data('visible-field'));
        var block = $(this);

        function check()
        {
            var values = block.data('values').split(',');

            if (values.indexOf(source.val())<0)
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
                    data: {service: currentService}
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
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/book/intervals",
                    data: {datetext: dateText, service: serviceNum}
                }).done(function( data ) {
                    //location.reload();
                });
            }
        });

        $('select[name="service_0"]').on('loaded.bs.select', function () {
            var Selectpicker = $('#number').data('selectpicker');
            console.log(Selectpicker)
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

})