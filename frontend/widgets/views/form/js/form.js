function formCopy(this)
{
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