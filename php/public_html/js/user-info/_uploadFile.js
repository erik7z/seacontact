function uploadFile($action, $file_types, $instructions) {

var $self = this;

this.instructions = $instructions;
// this.loading_progress
this.globalData;
this.ajaxUpl;

	new AjaxUpload(cv_file_btn, {
		action: $action, 
		onSubmit : function(file, ext){
			var matcher = new RegExp("^(" + $file_types + ")$");
		if (ext && matcher.test(ext)) {
			} else {
				alert("only doc, docx, xls, xlsx, pdf");
				return false;
			}
		},
		onComplete: function(file, response){
			response = JSON.parse(response);
			if(response.success) {
				$('.cv_link').attr('href', response.data.www);
				$.notify({
				  message: 'New Cv File Uploaded !' 
				},notify_success_settings);
				
			}
			$self.showResult($self, response)
		}

	});

this.showResult = function(self,response) {
	alert('This method to be overwritten');
}


}



