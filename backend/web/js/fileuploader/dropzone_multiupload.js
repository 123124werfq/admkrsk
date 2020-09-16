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

		var li_tmpl = function(data, index)
		{
			var file = data.preview;
			var fileName = data.name;
			var id_media = data.id;

			var description = data.description;

			if (description==null)
				description = '';

			var download = data.download;

			if (download==null)
				download = '';


			var author = data.author;

			if (author==null)
				author = '';

			if (!id_media)
				id_media = 0;

			if (!file)
				file = '';

			preview = '';

			var output = '<div class="file-row" class="files">\
						<div class="preview dz-preview">\
							<img src="'+file+'" data-dz-thumbnail />\
						</div>\
						<div class="media-data">\
						    <strong class="error text-danger" data-dz-errormessage></strong>\
						    <input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][file_path]" value="'+file+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][id_media]" value="'+id_media+'"/>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][ord]" rel="ord" value="'+index+'"/>\
							<input type="text" class="form-control" placeholder="Название" name="'+settings.relationname+'['+settings.group+']['+index+'][filename]" rel="name" value="'+fileName+'"/>';

			if (settings.showAuthor)
				output += '<input type="text" class="form-control" placeholder="Автор" name="'+settings.relationname+'['+settings.group+']['+index+'][author]" rel="name" value="'+author+'"/>';

				output += '<textarea class="form-control" placeholder="Описание" name="'+settings.relationname+'['+settings.group+']['+index+'][description]">'+description+'</textarea>\
							<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+index+'][grouptype]" rel="name" value="'+settings.group+'"/>\
						</div>\
						<div class="file-progress">\
						    <p class="size" data-dz-size></p>\
						</div>\
						<div class="file-buttons">\
							<a href="'+download+'" class="btn btn-success" download><i class="fa fa-download"></i></a>\
						</div>\
						<a class="dz-remove" href="javascript:undefined;" data-dz-remove="">×</a>\
					</div>';

			return output;
		}

		var settings = $.extend({
			relationname: 'Media',
			showAuthor: 0,
			group: 1,
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

			if (settings.records.length>0)
			{
				for (var i=0; i<settings.records.length; i++)
				{
					if (!settings.records[i].name)
						settings.records[i].name = settings.records[i].file_path;

					if (!settings.records[i].description)
						settings.records[i].description = settings.records[i].description;

					files_list.append($(li_tmpl(settings.records[i],i)));
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
				previewTemplate: '<div class="file-row" class="files">\
									<div class="preview dz-preview">\
										<img data-dz-thumbnail />\
										<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\
									</div>\
									<div class="media-data">\
									    <strong class="error text-danger" data-dz-errormessage></strong>\
									</div>\
									<div class="file-progress">\
									    <p class="size" data-dz-size></p>\
									</div>\
									<div class="file-buttons"></div>\
								</div>',
				init: function(){
			        this.on("success", function(file, response){

						response = JSON.parse(response);

						var element = '<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][file_path]" value="'+response.file+'"/>\
						<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][ord]" rel="ord" value="'+new_index+'"/>\
						<input class="form-control" placeholder="Название" type="text" name="'+settings.relationname+'['+settings.group+']['+new_index+'][filename]" value="'+file.name+'"/>';

						if (settings.showAuthor)
							element += '<input type="text" class="form-control" placeholder="Автор" name="'+settings.relationname+'['+settings.group+']['+new_index+'][author]" value=""/>';

						element+='<textarea class="form-control" placeholder="Описание" name="'+settings.relationname+'['+settings.group+']['+new_index+'][description]" value=""/>\
								<input type="hidden" name="'+settings.relationname+'['+settings.group+']['+new_index+'][grouptype]" rel="name" value="'+settings.group+'"/>';

			            $(file.previewElement).find('.media-data').append(element);

			            $(file.previewElement).find(".dz-progress").fadeOut();
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