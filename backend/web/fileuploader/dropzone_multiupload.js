(function ($){

	$.fn.multiupload = function( options ){

		var isVideo = function(path)
		{
			var ext = path.split('.').pop();
			ext = ext.toLowerCase();

			var exts = ['AVI','MPG','MPEG','MP4','M2TS','MOV','3GP','MKV']
			if (exts.indexOf(ext.toUpperCase())!==-1)
				return true;

			return false;
		}

		var li_tmpl = function(file, fileName, index, id_media, data)
		{
			if (!data)
				data = JSON.parse('{"name":"","description":""}');

			/*var description = fileName.split('_').join(' ').split('.');
			description.pop();
			description.join('.');*/

			if (!id_media)
				id_media = 0;

			if (!file)
				file = '';

			preview = '';


			if (settings.showPreview)
			{
				if (isVideo(file))
					preview = '<td width="190" align="center"><video width="150" height="100" controls="controls"><source src="'+file+'"></video></td>';
				else
					preview = '<td width="190" align="center"><img class="thumbnail" src="'+file+'"/></td>';
			}

			if (settings.tpl==1)
			{
				$output = '<tr id="file'+index+'">\
						'+preview+'\
						<td valign="top">\
							<input type="text" class="form-control" name="'+settings.relationname+'['+settings.group+']['+index+'][name]" rel="name" value="'+data.name+'" placeholder="Заголовок" />\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][file_path]" value="'+file+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][id_media]" value="'+id_media+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][ord]" rel="ord" value="'+index+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][grouptype]" rel="name" value="'+settings.group+'"/>'

				if (settings.showAuthor == 1)
					$output += '<input type="text" class="form-control" name="'+settings.relationname+'['+settings.group+']['+index+'][author]" rel="name" value="'+data.name+'" placeholder="Автор" />';

				$output += '<textarea maxlength="255" class="form-control" name="'+settings.relationname+'['+settings.group+']['+index+'][description]" rel="description" placeholder="Описание">'+data.description+'</textarea>\
						</td>\
						<td width="15"><a class="close btn btn-default" onclick="$(this).parent().parent().remove(); return false;">&times;</a></td>\
					</tr>';
			}
			else
				return '<div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">\
					    	<div class="dz-filename"><span data-dz-name></span></div>\
					    	<div class="dz-size" data-dz-size></div>\
					    	<div class="dz-image">\
							    <img src="'+file+'" data-dz-thumbnail />\
							</div>\
							<div class="dz-details"></div>\
							<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
							<div class="dz-success-mark"><svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">      <title>Check</title>      <defs></defs>      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path></g></svg> </div>\
							<div class="dz-error-mark"><span>✘</span></div>\
							<a href="javascript:" class="dz-remove" data-dz-remove>&times</a>\
							<div class="dz-error-message"><span data-dz-errormessage></span></div>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][file_path]" value="'+file+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][id_media]" value="'+id_media+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][ord]" rel="ord" value="'+index+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][name]" rel="name" value="'+fileName+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][grouptype]" rel="name" value="'+settings.group+'"/>\
						</div>';
		}

		var settings = $.extend({
			relationname: 'Media',
			group: 1,
			showAuthor: 0,
			tpl: 1,
			single:false,
			records: [],
			allowedExtensions: [],
			showPreview:false
		}, options );

		// раставляет сортировочный индекс при перемещении
		var reOrdFiles = function(container,selector){
			container.find(selector).each(function(i){
				$(this).find('input[rel="ord"]').val($(this).index());
			});
		}

		return this.each(function(){
			var element = this;

			var new_index = settings.records.length;

			//var files_list = $('<div class="file-uploaded"></div>');

			var files_list = $(this);

			if (settings.tpl==1)
			{
				files_list.append('<table class="file-uploaded-container table sortable" width="100%" cellspacing="0" cellpadding="0"></table');

				/*if (!settings.single)
				{
					var table = files_list.find('table');
					table.sortable({
						items: 'tr',
						stop: function(event, ui){
							reOrdFiles(table,'tr');
						}
					}).disableSelection();
				}*/
			}
			else
			{
				files_list.sortable({
					items: '.dz-preview',
					stop: function(event, ui){
						reOrdFiles(files_list,'.dz-preview');
					}
				}).disableSelection();
			}

			//$(this).after(files_list);

			//files_list = files_list.find('.file-uploaded-container');

			if (settings.records.length>0)
			{
				for (var i=0; i<settings.records.length; i++)
				{
					if (!settings.records[i].name)
						settings.records[i].name = settings.records[i].file_path;

					files_list.append($(li_tmpl(settings.records[i].preview, settings.records[i].name, i, settings.records[i].id,settings.records[i])));
				}
			}

			var uploader = $(element).dropzone({
				addRemoveLinks: true,
				url: "/media/upload",
				//addRemoveLinks: "/media/delete",
				dictRemoveFile: '×',
				dictCancelUpload: '×',
				resizeWidth: 1920,
				/*previewTemplate: '<div class="dz-preview dz-file-preview">\
									<div class="dz-details">\
								    <div class="dz-filename"><span data-dz-name></span></div>\
								    <div class="dz-size" data-dz-size></div>\
								    	<img data-dz-thumbnail />\
								    <input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][file_path]" value=""/>\
									<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][ord]" rel="ord" value=""/>\
									<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][name]" rel="name" value=""/>\
									<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][grouptype]" rel="name" value="'+settings.group+'"/>\
								  </div>\
								  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
								  <div class="dz-success-mark"><span>✔</span></div>\
								  <div class="dz-error-mark"><span>✘</span></div>\
								  <div class="dz-error-mark"><span>✘</span></div>\
								  <div class="dz-error-message"><span data-dz-errormessage></span></div>\
								</div>',*/
				init: function(){
			        this.on("success", function(file, response){

			        	response = JSON.parse(response);
			            $(file.previewElement).append(
			        	'<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][file_path]" value="'+response.file+'"/>\
						<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][ord]" rel="ord" value="'+new_index+'"/>\
						<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][name]" rel="name" value="'+file.name+'"/>\
						<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][grouptype]" rel="name" value="'+settings.group+'"/>');

						new_index++;
			      	});
				}
			});

			/*var uploader = new qq.FileUploader({
				element: element,
				action: '/upload.php',
				name: 'images',
				allowedExtensions: settings.allowedExtensions,
				onSubmit: function(id, fileName){
				},
				onComplete: function(id, fileName, responseJSON)
				{
					if (typeof(responseJSON.error)=='string')
						return false;

					if (!settings.single)
					{
						files_list.append($(li_tmpl(responseJSON.filelink, fileName, new_index, 0)));
						new_index++;
					}
					else
					{
						files_list.html('');
						files_list.append($(li_tmpl(responseJSON.filelink, fileName, new_index, 0)));
					}
				},
			});*/

			$(".dz-remove").click(function(){
				$(this).parent().remove();
				return false;
			});

			var active_file = 0;
			/*var deleteSingleFile = function(){
				$(".qq-upload-drop-area span").html('');
				$("#fnewfile").val('');
				$("#singleField").remove();

				return false;
			}*/
		});
	};
}(jQuery));