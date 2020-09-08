toastr.options = {
    closeButton: true,
    progressBar: true,
    showMethod: 'slideDown',
    timeOut: 5000
};

function getValueById(id)
{
    if ($("#"+id).length>0)
        return $("#"+id).val();

    return '';
}

function removeRow(obj)
{
  var parent = $(obj).parent().parent();

  if (parent.siblings().length>0)
      parent.remove();
  else
      parent.find("input,textarea").val('');

  return false;
}

function setVisisble()
{
    $("div[data-visible-field]").each(function(){

        var source = $("#"+$(this).data('visible-field'));
        var block = $(this);

        function check()
        {
            var values = String(block.data('values')).split(',');

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
}

var csrf, csrf_value;

function filePicker(callback, value, meta) {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    //input.setAttribute('accept', 'image/*');
    input.click();
}

var tinymceConfig = {
    selector:'.redactor',
    plugins: [
        'link image imagetools table autoresize collections gallery code paste media lists fullscreen stickytoolbar form hrreserve pagenews faq recordSearch map faqcollection'
    ],
    menu: {
        custom: { title: 'Плагины', items: 'form gallery collections hrreserve pagenews faq recordSearch map faqcollection'}
    },
    menubar: 'file edit view insert format tools table custom',
    contextmenu: "link image imagetools table spellchecker",
    toolbar: "code fullscreen | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist blockquote | link image media",
    language: 'ru',
    extended_valid_elements : "faq[data-ids|data-category],map[data-id],searchrecord[data-encodedata],pagenews[data-id],hrreserve[pagesize],collection[data-id|data-encodedata],gallery[data-id|data-limit|data-type],forms[data-id|data-data]",
    content_css : "/js/tinymce/admin.css",
    image_title: true,
    images_upload_url: '/media/tinymce',
    automatic_uploads: true,
    paste_data_images: true,
    file_picker_types: 'file image',
    sticky_offset: 0,
    convert_urls: 0,
    /*file_picker_callback: function(callback, value, meta) {
        // Provide file and text for the link dialog
        if (meta.filetype == 'file') {
            filePicker(callback, value, meta);
        }
    },*/

    /*file_picker_callback : function (cb, value, meta) {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.onchange = function () {
        var file = this.files[0];

        var reader = new FileReader();
        reader.onload = function () {
          var id = 'blobid' + (new Date()).getTime();
          var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
          var base64 = reader.result.split(',')[1];
          var blobInfo = blobCache.create(id, file, base64);
          blobCache.add(blobInfo);
          cb(blobInfo.blobUri(), { title: file.name });
        };
        reader.readAsDataURL(file);
      };
      input.click();
    },*/
    formats: {
        removeformat: [
            {
                selector: 'b,strong,em,i,font,u,strike,sub,sup,dfn,code,samp,kbd,var,cite,mark,q,del,ins,blockquote',
                remove: 'all',
                split: true,
                block_expand: true,
                expand: false,
                deep: true
            },
            { selector: 'span', attributes: ['style', 'class'], remove: 'empty', split: true, expand: false, deep: true },
            { selector: '*', attributes: ['style', 'class'], split: false, expand: false, deep: true }
        ]
    }
};

tinymce.init(tinymceConfig);

/*tinymce.activeEditor.uploadImages(function(success) {
  $.post('media/tinymce', tinymce.activeEditor.getContent()).done(function() {
  console.log("Uploaded images and posted content as an ajax request.");
  });
});*/

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
        $dom.find("#formdynamic-input"+id_vinput+", .formdynamic-input"+id_vinput+" input[name*='input"+id_vinput+"']").change(function(){

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

function formTemplateSortable()
{
  $("#form-template_pjax ").sortable({
      stop: function(event, ui){
        var ords = [];
        $("#form-template .form-row").each(function(i){
          ords.push($(this).data('id'));
        });
        $.ajax({
            url: '/form/order',
            type: 'post',
            data: {
              _csrf: csrf_value,
              ords:ords
            },
            success: function(data)
            {

            }
        });
      }
    }).disableSelection();

  $(".form-row").sortable({
      start: function(event, ui) {
          ui.item.data('pos', ui.item.index()+'_'+ui.item.parent().data('id'));
      },
      stop: function(event, ui){

          if (ui.item.data('pos') != ui.item.index()+'_'+ui.item.parent().data('id'))
          {
            var ords = [];
            var parents = [];
            var $block = $(this);

            ui.item.parent().children().each(function(i){
                ords.push($(this).data('id'));
                parents.push(ui.item.parent().data('id'));
            });

            $.ajax({
                url: '/form-element/order',
                type: 'post',
                data: {ords: ords, parents: parents, _csrf: csrf_value},
                success: function(data)
                {
                  toastr.success('Порядок изменен', '');
                }
            });
          }
      },
      connectWith: ".form-row",
    }).disableSelection();
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
            input_name = input_name.replace('['+(data_row-1)+']','['+data_row+']');// input_name.substr(0,input_name.indexOf('0'))+count+input_name.substr(input_name.indexOf('0')+1);

            clone_input.attr('id',input_id+data_row).attr('name',input_name).val('');

            if ((clone_input).hasClass('autocmpl'))
                runAutocomplete(clone_input);

            if ((clone_input).hasClass('timepicker'))
            clone_input.attr('value',int2Time(clone_input.val())).after('<span class="add-on"><i class="icon-time"></i></span>').timepicker();

            if ((clone_input).hasClass('redactor')) {
                var cloneConfig = JSON.parse(JSON.stringify(tinymceConfig));
                cloneConfig.selector = '#' + clone_input.attr('id');
                clone_input.show();
                tinymce.init(cloneConfig);
            }
        }
    });

    if ($("#"+block).hasClass('sortable'))
    {
        input_row.find("input[name*='ord]']").val(input_row.index());
    }

    return false;
}

function reordModels($block,$data)
{
  var ords = [];

  $block.children().each(function(i){
    if ($(this).data('key')!=undefined)
      ords.push($(this).data('key'));
    else
      ords.push($(this).data('id'));
  });

  if ($block.prop("tagName")=='TBODY')
    var url = $block.parent().data('order-url');
  else
    var url = $block.data('order-url');

  $.ajax({
      url: url,
      type: 'post',
      data: {ords: ords, _csrf: csrf_value},
      success: function(data)
      {
        toastr.success('Порядок изменен', '');
      }
  });
}

function addDashboardPin()
{
  $('.page-heading h2').css('display', 'inline-block').after("<div class='dashboard-pin'><i class='fa fa-thumb-tack'></i></div>");

  $('#dash-save').click(function(){
    $.ajax({
      url: '/site/savelink',
      type: 'post',
      data: {name: $('.page-heading h2').text(), url: location.href, _csrf: csrf_value},
      success: function(data)
      {
        toastr.success('Сыылка сохранена', '');
      }
    });
    $('#dashboard-modal').modal('hide');
  });

  $('.dashboard-pin').click(function(){
    $('#dash-link').val(location.href);
    $('#dash-name').val($('.page-heading h2').text());
    $('#dashboard-modal').modal();
  });
}


jQuery(document).ready(function()
{
    $("body").delegate('.dz-remove','click',function(){

        var form = $(this).closest('form');
        $(this).closest('.fileupload_item').remove();
        recalculateFormSize(form);
        return false;

    });

    $(".selectActionDropDown a").click(function(){

      var $link = $(this);
      if ($('input[name="selection[]"]:checked').length==0)
      {
          alert('Вы не выбрали ниодной записи');
      }
      else
      {
        var ids = [];

        $('input[name="selection[]"]:checked').each(function(i){
            ids.push($(this).val());
        });

        $.ajax({
            url: document.location,
            type: 'post',
            data: {ids:ids,action:$link.data('action')},
            success: function(data)
            {
              $.pjax.reload({container: '#collection_grid', async: false});
              toastr.success('Готово', '');
            }
        });
      }

      return false;
    });

    $("body").delegate('input[name="selection[]"]','change',function(){
      if ($(this).is(':checked'))
      {
        $('.grid-view table').addClass('hasChecked');
        $(".selectActionForm").removeClass('hide');
      }
      else
      {
        if ($('input[name="selection[]"]:checked').length==0)
        {
          $('.grid-view table').removeClass('hasChecked');
          $(".selectActionForm").addClass('hide');
        }
      }

      $("#selectCount").html($('input[name="selection[]"]:checked').length);

    });
    $("#redactor-modal button[type=submit]").click(function(){
      $("#redactor-modal form").submit();
      return false;
    });

    $('body').delegate(".showdetails",'change',function(){
      $(this).parent().next().toggleClass('hide');
    });

     $('body').delegate("#collectionrecordsearchform-id_collection",'change',function(){
        $form = $("#collection-redactor");

        $.ajax({
            url: $form.attr('action'),
            type: 'post',
            data: $form.serialize(),
            success: function(data)
            {
              $(".modal-body").html(data);
              setVisisble();
            }
        });
    });

    $("#collection-redactor #collection-id_parent_collection").change(function(){
        $form = $("#collection-redactor");

        $.ajax({
            url: $form.attr('action'),
            type: 'post',
            data: $form.serialize(),
            success: function(data)
            {
              $("body").html(data);
              setVisisble();
            }
        });
    });

    $('body').delegate(".visible-field, #forminput-type, #forminput-id_collection",'change',function(){
        var $form = $("#FormElement form");

        $.ajax({
            url: $form.attr('action'),
            type: 'post',
            data: $form.serialize(),
            success: function(data)
            {
              $("#FormElement .modal-body").html(data);
              setVisisble();
            }
        });
    });

    $("#FormElement .btn-primary").click(function(){

      var $form = $("#FormElement form");

      tinyMCE.triggerSave();

      $.ajax({
          url: $form.attr('action'),
          type: 'post',
          data: $form.serialize()+'&submit=1',
          success: function(data)
          {
            if (data=='')
            {
               $.pjax.reload({container: '#form-template_pjax', async: false});
              $('#FormElement').modal('hide');
            }
            else
            {
              $("#FormElement .modal-body").html(data);
              setVisisble();
            }
          }
      });
    });

    $("body").delegate(".add-row, .delete-row",'click',function(){

      var $link = $(this);

      $.ajax({
          url: $link.attr('href'),
          type: 'post',
          data: {
            _csrf: csrf_value,
          },
          success: function(data)
          {
            toastr.success('Изменено', '');
            $.pjax.reload({container: '#form-template_pjax', async: false});
          }
      });

      return false;
    });

    $("#form-template").delegate(".create-form-input, .update-input, .create-element, .update-rowm, .create-subform", 'click',function(){

      var $link = $(this);

      $('#FormElement').modal({
        keyboard: false
      });

      $.ajax({
          url: $link.attr('href'),
          type: 'post',
          data: {
            _csrf: csrf_value,
          },
          success: function(data)
          {
            $("#FormElement .modal-body").html(data);
            setVisisble();
          }
      });

      return false;
    })

    $('.dataTables').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
             customize: function (win){
                  $(win.document.body).addClass('white-bg');
                  $(win.document.body).css('font-size', '10px');
                  $(win.document.body).find('table')
                          .addClass('compact')
                          .css('font-size', 'inherit');
             }
            }
        ]
    });

    $("#CollectionRecord button.btn-primary").click(function(){
      $("#CollectionRecord form").submit();
    });

    $("#CollectionRecord").delegate('form','submit',function(){

        event.preventDefault();

        var $form = $("#CollectionRecord form");

        $form.find('.has-error .help-block').html('');
        $form.find('.has-error').removeClass('has-error');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: $form.attr('action'),
            data: $form.serialize()
        }).done(function(data){

            if (data.success)
            {
              $.pjax.reload({container: '#collection_grid', async: false});

              $("#CollectionRecord").modal('hide');
            }
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

        return false;
    });

    csrf = $('meta[name=csrf-param]').prop('content');
    csrf_value = $('meta[name=csrf-token]').prop('content');

    $(".table-responsive").delegate('.update-record a','click',function(){

      var $link = $(this);

      $.ajax({
          url: $link.attr('href'),
          type: 'post',
          data: {
            _csrf: csrf_value,
          },
          success: function(data)
          {
            $("#CollectionRecord .modal-body").html(data);
          }
      });

      $('#CollectionRecord').modal({
        keyboard: false
      });

      return false;
    });

    $(".create-collection").click(function(){
      var $link = $(this);

      $.ajax({
          url: $link.attr('href'),
          type: 'post',
          data: {
            _csrf: csrf_value,
          },
          success: function(data)
          {
            $("#CollectionRecord .modal-body").html(data);
          }
      });

      $('#CollectionRecord').modal({
        keyboard: false
      });

      return false;
    });

    $("#saveOrd").click(function(){
      reordModels($("#blocks"));
      return false;
    })

    formTemplateSortable();

    $(document).on("pjax:success", "#form-template_pjax",  function(event){
      formTemplateSortable();
    });


    $("#blocks").sortable({
      stop: function(event, ui){
        $("#saveOrd").show();
        $("#blocks > div").each(function(i){
          $(this).find('input').val(i);
        });
      }
    }).disableSelection();

    $(".menu-container, .menu-childs").sortable({
      connectWith: ".menu-childs, .menu-container",
      stop: function(event, ui){
        var data = {};

        data['_csrf'] = csrf_value;

        var ords = [];

        ui.item.parent().children().each(function(i){
            ords.push($(this).data('id'));
        });

        data['ords'] = ords;

        data[$(".menu-container").data('model')] = {
          id_parent:ui.item.parent().data('id')
        };

        $.ajax({
            url: '/menu-link/update?id='+ui.item.data('id'),
            type: 'post',
            data: data,
            success: function(data)
            {
            }
        });

        $(".menu-container").removeClass('start-sorting');
      },
      start: function( event, ui ) {
        $(".menu-container").addClass('start-sorting');
      }
    }).disableSelection();

    $("body").delegate('.multiyiinput .close,.multiinput .close','click',function(){
      $(this).parent().parent().remove();
      return false;
    });

    $(".import-collection-start").change(function(){

      var $table = $(this).closest('.panel-body').find('table');

      if ($(this).val()>0)
      {
        $table.find('.disable').removeClass('disable');
        $table.find('tr').slice(0,$(this).val()).addClass('disable');
      }
      else
        $table.find('.disable').removeClass('disable');

    });

     $(".import-collection-key").change(function(){

      var $table = $(this).closest('.panel-body').find('table');

      if ($(this).val()>0)
      {
        $table.find('.keyrow').removeClass('keyrow');
        $table.find('tr:eq('+($(this).val()-1)+')').addClass('keyrow');
      }
      else
        $table.find('.keyrow').removeClass('keyrow');

    });

    $(".sortable").sortable({
      stop: function(event, ui){
        $(this).find('.row').each(function(i){
            $(this).find("input[name*='ord]']").val($(this).index());
        });

          /*if ($(this).prop("tagName")=='TBODY')
            $this = $(this).parent();
          else
            $this = $(this);

          var id = ui.item.data('id');
          var pos = ui.item.index();
          var table = $this.data('table');
          var where = $this.data('where');
          var pk = $this.data('pk');
          if (table)
              $.ajax({
                  url: '/master/default/reord',
                  type: 'post',
                  data: 'id='+id+'&pos='+pos+'&table='+table+'&where='+where+'&pk='+pk,
                  success: function(data)
                  {
                  }f
              });*/
      }
    }).disableSelection();

    $(".ordered tbody, ul.ordered").sortable({
      stop: function(event, ui){

          reordModels($(this));

          /*if ($(this).prop("tagName")=='TBODY')
            $this = $(this).parent();
          else
            $this = $(this);

          var id = ui.item.data('id');
          var pos = ui.item.index();
          var table = $this.data('table');
          var where = $this.data('where');
          var pk = $this.data('pk');

          if (table)
              $.ajax({
                  url: '/site/reord',
                  type: 'post',
                  data: 'id='+id+'&pos='+pos+'&table='+table+'&where='+where+'&pk='+pk,
                  success: function(data)
                  {

                  }
              });*/
      }
  }).disableSelection();

  addDashboardPin();
});