function setVisisble()
{
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
        'link image imagetools table autoresize collections gallery code paste media lists fullscreen stickytoolbar form'
    ],
    contextmenu: "link image imagetools table spellchecker",
    toolbar: "code fullscreen | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist blockquote | link image media form | collections gallery ",
    language: 'ru',
    extended_valid_elements : "collection[data-id],gallery[data-id|data-limit|data-type],forms[data-id]",
    content_css : "/js/tinymce/admin.css",
    image_title: true,
    images_upload_url: '/media/tinymce',
    automatic_uploads: true,
    paste_data_images: true,
    file_picker_types: 'file image',
    sticky_offset: 0,
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


function addInput(block)
{
    //var id = $("#"+block+" input").eq(-1).attr("id");

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

toastr.options = {
    closeButton: true,
    progressBar: true,
    showMethod: 'slideDown',
    timeOut: 1000
};

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

jQuery(document).ready(function()
{
    $("#FormElement .btn-primary").click(function(){
      $(".modal-body form").submit();
    });

    $("#form-template").delegate(".create-form-input, .update-input, .create-element",'click',function(){
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
            //$("#form-template").append(data);
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

    $("#form-template .ibox-content").sortable({
      stop: function(event, ui){
        var ords = [];
        $("#form-template .ibox-content .form-row").each(function(i){
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

    $("#blocks").sortable({
      stop: function(event, ui){
        $("#saveOrd").show();
        $("#blocks > div").each(function(i){
          $(this).find('input').val(i);
        });
      }
    }).disableSelection();

    $(".menu-container,.menu-childs").sortable({
      connectWith: ".menu-childs, .menu-container",
      stop: function(event, ui){

        $.ajax({
            url: 'update?id='+ui.item.data('id'),
            type: 'post',
            data: {
              _csrf: csrf_value,
              MenuLink: {
                ord:ui.item.index(),
                id_parent:ui.item.parent().data('id')
              }
            },
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

    $(".multiyiinput, .multiinput").delegate('.close','click',function(){
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

    $(".form-row").sortable({
      stop: function(event, ui){
          var ords = [];
          var parents = [];
          var $block = $(this);

          ui.item.parent().children().each(function(i){
              ords.push(ui.item.data('id'));
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
      },
      connectWith: ".form-row",
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
});