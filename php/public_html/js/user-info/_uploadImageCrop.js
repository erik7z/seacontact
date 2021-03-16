function uploadPics($action ,$aspect_ratio) {

var $self = this;

var uploaded_original, img_www, img_w, img_h, crop_w, crop_h, sel_ratio = 1;
this.popup = $('#edit_avatar_modal');
this.save_button = $("#butSave"); //!!!!
this.instructions = $('h3.header'); //!!!!

this.sel_area = $('#selection_area');
this.loading_progress = $('div.loading-thumb');
this.preview_big = $("#preview_big");
this.preview_small = $("#preview_small");
this.avatar_big = $("#avatar_big");
this.avatar_small = $("#avatar_small");
this.edit_window = $("#edit_window");

this.globalData;
this.ajaxUpl;
this.imgObj;

		new AjaxUpload(avatar_uppload_btn, {
			action: $action, 
			onSubmit : function(file, ext){
			if (ext && /^(jpg|png|jpeg|gif)$/.test(ext)) {
				$self.preview_big.addClass('hide');
				$self.preview_small.addClass('hide');
				$self.loading_progress.removeClass('hide');
				$self.popup.modal('show');
				} else {
					alert("only jpg, png, jpeg, gif");
					return false;
				}
			},
			onComplete: function(file, response){
				response = JSON.parse(response);

				if(response.success) {
					$self.imgObj = response.data;
					$self.ajaxUpl = this;
					var img_max_w = $self.popup.find('.modal-content').width() * 0.6;
					img_w = $self.imgObj.img_w;
					img_h = $self.imgObj.img_h;
					crop_w = $self.imgObj.crop_w;
					crop_h = $self.imgObj.crop_h;
					if(img_w > img_max_w) {
						sel_ratio = img_w / img_max_w;
						var img_ratio = img_h / img_w;
						img_w = img_max_w;
						img_h = img_w * img_ratio;
					}
					img_www = $self.imgObj.img_www;
					uploaded_original = $self.imgObj.img;

					$self.edit_window.removeClass('hide');
					$self.sel_area
							.find('img').remove()
							.css({'width' : img_w, 'height' : img_h});

					$('<img></img>',{
						src: img_www,
						width: img_w,
						height: img_h
					}).prependTo($self.sel_area);
					$self.loading_progress.hide();
					$('.modal-backdrop').remove();
					$self.popup.on('hidden.bs.modal', $self.deleteUnloadedAvatar);

					$self.cropView(img_www);
				} else alert('Oooops! some upload error....');

			}

		});

this.deleteUnloadedAvatar = function() {
	$.get('/cv/clean-not-loaded-avatar?file_name='+$self.imgObj.img,{request: 'json'}, function(response) {
		if(response.success) {
		} else alert('Some Error : '+response.data);
	}, 'json');
}

this.cropView =	function (imgName) {

			$self.avatar_big.css({
				'width': crop_w,
				'height': crop_h
			});

			$self.avatar_small.css({
				'width': 100,
				'height': 100
			});

			$self.preview_big.css({
				'width': 180, 
				'height': 270
			}).removeClass('hide');

			// $self.preview_small.css({
			// 	'width': 100, 
			// 	'height': 100
			// }).removeClass('hide');
			
			$self.sel_area.imgAreaSelect({aspectRatio: $aspect_ratio, handles: true, fadeSpeed: 200,onInit:$self.preview, onSelectChange: $self.preview});
			$self.sel_area.unbind('hover');
			$self.sel_area.bind('hover', function() {
			    $(this).css('cursor', 'crosshair');
			});
			$self.save_button.removeClass('hide')
				.bind('click', function(e) {
  					 $self.save(e);
				});
			$self.instructions.text('Select prefered part');
			$self.avatar_big.attr("src", imgName);
			$self.avatar_small.attr("src", imgName);
		}


this.preview =	function (img, selection) {
			if (!selection.width || !selection.height)	return;

			var scaleX = crop_w / selection.width;
			var scaleY = crop_h / selection.height;

			$self.avatar_big.css({
				width: Math.round(scaleX * img_w),
				height: Math.round(scaleY * img_h),
				marginLeft: -Math.round(scaleX * selection.x1),
				marginTop: -Math.round(scaleY * selection.y1)
			});

			$self.avatar_small.css({
				width: Math.round(scaleX * img_w),
				height: Math.round(scaleY * img_h),
				marginLeft: -Math.round(scaleX * selection.x1),
				marginTop: -Math.round(scaleY * selection.y1)
			});
			
			$("#x1").val(selection.x1);
			$("#y1").val(selection.y1);
			$("#x2").val(selection.x2);
			$("#y2").val(selection.y2);
			$("#w").val(selection.width);
			$("#h").val(selection.height);
			
		};

this.save = function (e) {
			e.preventDefault();

			$self.before_save($self);

			var x1 = $("#x1").val();
			var y1 = $("#y1").val();
			var x2 = $("#x2").val();
			var y2 = $("#y2").val();
			var w = $("#w").val();
			var h = $("#h").val();

			if(!x1 || !x2 || !y1 || !y2 || !w || !h) {
				x1 = 0;
				y1 = 0;
				x2 = w = img_w;
				y2 = h = img_h;
			}

			if(sel_ratio != 1) {
				x1 = x1 * sel_ratio;
				x2 = x2 * sel_ratio;
				y1 = y1 * sel_ratio;
				y2 = y2 * sel_ratio;
				w = w * sel_ratio;
				h = h * sel_ratio;
			}

			$.ajax ({
				url: $action,
				type: "POST",
				dataType: 'json',
				data: {image: uploaded_original, x1: x1, y1: y1, x2: x2, y2: y2, w: w, h: h},
				error: function (data) {
					console.log(data);
				},
				success: function (data) {
					$self.popup.off('hidden.bs.modal', $self.deleteUnloadedAvatar);
					// data = JSON.parse(data); // двойной json encode на сервере
					$self.loading_progress.hide();
					$self.showResult($self, data);
					$self.globalData = data;
					$self.popup.modal('hide');
					location.reload();
				}
			});

}

this.before_save = function (self) {
			self.loading_progress.removeClass('hide');
}

this.showResult = function(self, img_wwwNames) {
	alert('This method to be overwritten');
}


}



