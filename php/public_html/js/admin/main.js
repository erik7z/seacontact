var mce_mail_text = {
		mode:'exact',
		elements: 'text',
		menubar: false,
		statusbar: false,
		plugins: ['advlist autolink lists link charmap hr searchreplace nonbreaking paste textcolor colorpicker textpattern'],  
    toolbar: "newdocument undo redo | bold italic underline | alignleft aligncenter alignright | fontsizeselect bullist numlist link",
		toolbar_items_size : 'small',
};

var mce_mail_signature = {
		mode:'exact',
		elements: 'signature',
		menubar: false,
		statusbar: false,
		toolbar: "bold italic underline",
		toolbar_items_size : 'small',
};


var table_defaults = {
		"aaSorting": [],
        "bPaginate": true,
        "iDisplayLength": 50,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "aoColumnDefs" : [ {
            "bSortable" : false,
            "aTargets" : [ "no-sort" ]
        } ]
      };

var multiselect_settings = {
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		maxHeight: 400,
		numberDisplayed: 2
	};

var notify_success_settings = {
            type: 'success',
            mouse_over: 'pause',
            offset: 10,
            placement: {
                from: "bottom",
                align: "right"
              },
            animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            },  
          };


var notify_warning_settings = {
            type: 'warning',
            mouse_over: 'pause',
            offset: 10,
            placement: {
                from: "bottom",
                align: "right"
              },
            animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            },  
          };

var notify_error_settings = {
            type: 'error',
            delay: 0,
            offset: 10,
            placement: {
                from: "bottom",
                align: "right"
              },
            animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            },  
          };