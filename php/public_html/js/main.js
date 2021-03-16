var multiselect_settings = {
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		maxHeight: 400,
		numberDisplayed: 2
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

var cv_upload_settings = {
        browseClass: "btn btn-success btn-lg",
        browseIcon: "<i class=\"glyphicon glyphicon-floppy-open\"></i> ",
        buttonLabelClass: '',
        maxFileCount: 1,
        autoReplace: true,
        allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xslx'],
        showCaption: false,
        showRemove: false,
        showUpload: false,
    };

var images_upload_settings = {
      browseClass: "btn btn-info btn-xs",
      removeClass: "btn btn-xs",
      browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
      browseLabel: 'Photo',
      maxFileCount: 10,
      autoReplace: true,
      previewSettings: {
          image: {width: "auto", height: "60px"},
      },
      allowedFileTypes: ["image"],
      showCaption: false,
      showUpload: false,
  };

var cv_avatar_upload_settings = {
         overwriteInitial: true,
         maxFileSize: 1500,
         showClose: false,
         showCaption: false,
         browseLabel: '',
         removeLabel: '',
         browseClass: "btn btn-default btn-sm",
         removeClass: "btn btn-default btn-sm",
         browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
         removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
         previewSettings: {
             image: {width: "160px", height: "auto"},
         },
         layoutTemplates: {main2: '{preview} ' + '{browse}  {remove} '},
         elErrorContainer: '#kv-avatar-errors',
         msgErrorClass: 'alert alert-block alert-danger',
         allowedFileExtensions: ["jpg", "png", "gif"]
     };

var popupSuccess = function ($message, $code) {
  console.log('function to be run on popup success action');
};
var popupFail = function ($message, $code) {
  console.log('function to be run on popup fail action');
};

function setLocation(url){
  if(url != window.location){
    try {
      history.pushState(null, null, url);
      return;
    } catch(e) {}
    location.hash = '#' + url;
  }
}

function xhrAction() {

  var $self = this;

  // ---------- XHR DELETE ------------
  $('body').on('click', 'a[data-xhrdelete]', function(e){
    var url = $(this).attr('href');
    var xhr_targ = $(this).data('xhrtarg');
    var xhr_id = $(this).data('xhrdelete');
    if(xhr_targ == 'undefined' || xhr_targ == undefined) return true;
    e.preventDefault();
    var targets = xhr_targ.split(',');
    
    if(targets.length > 0) {
      e.preventDefault();
      for (var i = targets.length - 1; i >= 0; i--) {
        var $ctr = $('[data-xhrctr='+targets[i]+']');
        $ctr.css('opacity', 0.6);
      }

      $.get(url, function(response) {
        if(response.success == 1) {

          var success_action = true;
          if($self['success_'+xhr_id] == 'undefined' || $self['success_'+xhr_id] == undefined) var success_action = false;
          if(success_action) $self['success_'+xhr_id](response, xhr_id);

          for (var i = targets.length - 1; i >= 0; i--) {
            var $ctr = $('[data-xhrctr='+targets[i]+']');
            $ctr.fadeOut('slow', function(){
              $ctr.remove();
            });
          }

          $.notify({message: response.message },notify_success_settings);

          var complete_action = true;
          if($self['complete_'+xhr_id] == 'undefined' || $self['complete_'+xhr_id] == undefined) var complete_action = false;
          if(complete_action) $self['complete_'+xhr_id](response, xhr_id);

        } else {$ctr.css('opacity', 1); $.notify({message: response.message },notify_error_settings);}
         
      },'json');
    }

  });

  // ---------- XHR TOGGLE ------------
  $('body').on('click', 'a[data-xhrtoggle]:not(.no_follow)', function(e){
    
    var url = $(this).attr('href');
    var xhr_targ = $(this).data('xhrtarg');
    var xhr_id = $(this).data('xhrtoggle');
    var contenturl = $(this).data('contenturl');
    if(xhr_targ == 'undefined' || xhr_targ == undefined) return true;
    var targets = xhr_targ.split(',');
 
    if(targets.length > 0) {
      e.preventDefault();

      for (var i = targets.length - 1; i >= 0; i--) {
        var $ctr = $('[data-xhrctr='+targets[i]+']');
        var active_url = $ctr.data('activeurl');
        $ctr.css('opacity', 0.6);
        // $('<div></div>', {
        //   class: 'loading-fixed',
        // }).appendTo($ctr);
      }

      $.get(url, function(response) {
        if(response.success == 1) {

          var success_action = true;
          if($self['success_'+xhr_id] == 'undefined' || $self['success_'+xhr_id] == undefined) var success_action = false;
          if(success_action) $self['success_'+xhr_id](response, xhr_id);

          if(targets.length > 1 || contenturl == 'undefined' || contenturl == undefined) contenturl = active_url;

          $.get(contenturl,function(response) {
            if(response.success == 1) {
              var $r = $(response.data);
              for (var i = targets.length - 1; i >= 0; i--) {
                $('[data-xhrctr='+targets[i]+']').replaceWith($r.find('[data-xhrctr='+targets[i]+']'));
              }

            } else { $ctr.css('opacity', 1); $.notify({message: response.message },notify_error_settings);}
          },'json');

          var complete_action = true;
          if($self['complete_'+xhr_id] == 'undefined' || $self['complete_'+xhr_id] == undefined) var complete_action = false;
          if(complete_action) $self['complete_'+xhr_id](response, xhr_id);

        } else {$ctr.css('opacity', 1); $.notify({message: response.message },notify_error_settings);}
         
      },'json');
    }
  });

  // ---------- XHR MENU ------------
  $('body').on('click', '[data-xhrmenu] a:not(.no_follow)', function(e){
    var url = $(this).attr('href');
    var xhr_targ = $(this).parents('[data-xhrmenu]').data('xhrtarg');
    var xhr_id = $(this).parents('[data-xhrmenu]').data('xhrmenu');

    if(xhr_targ == 'undefined' || xhr_targ == undefined) return true;

    var targets = xhr_targ.split(',');

    if(targets.length > 0) {
      e.preventDefault();

      for (var i = targets.length - 1; i >= 0; i--) {
        var $ctr = $('[data-xhrctr='+targets[i]+']');
        $ctr.css('opacity', 0.6);
        $('<div></div>', {
          class: 'loading-fixed',
        }).appendTo($ctr);
      }

      $.get(url, function(response) {
        if(response.success == 1) {
          setLocation(url);
          var $r = $(response.data);
          for (var i = targets.length - 1; i >= 0; i--) {
            $('[data-xhrctr='+targets[i]+']').replaceWith($r.find('[data-xhrctr='+targets[i]+']'));
          }

          $('[data-xhrmenu='+xhr_id+']').replaceWith($r.find('[data-xhrmenu='+xhr_id+']'));
          $('.loading-fixed').remove();
          var complete_action = true;
          if($self['complete_'+xhr_id] == 'undefined' || $self['complete_'+xhr_id] == undefined) var complete_action = false;
          if(complete_action) $self['complete_'+xhr_id](response, xhr_id);

        } else {$ctr.css('opacity', 1); $.notify({message: response.message },notify_error_settings);}
      },'json');
    }


  });

}

$(function() {
  xhrAction = new xhrAction();

  /* XHR SETTINGS */

  xhrAction.success_toggle_answer = function(response) {
    $('.section-questions .answer-toggle .fa-times').remove();
    $('.section-questions .answer-toggle').removeClass('change-icon').addClass('answer-accept').removeClass('answer-toggle');
  }
  xhrAction.complete_toggle_answer = function(response) {
    initGridMaker();
  }

  /* //XHR */
});





function notifyError(response)
{
  if(typeof(response.message) == 'object') {
    $.each(response.message, function(fieldset_name){
      var message = 'Fieldset: <b>'+fieldset_name+'</b> ';
      $.each(this, function(field_name){
        $.notify({message: message + '<br /> Check values for: <b>'+field_name+'</b>' },notify_warning_settings);
      });
    });
  } else {
      if(response.code == '401') $("html, body").animate({ scrollTop: $('.auth_container').offset().top }, "slow");
      $.notify({message: response.message },notify_warning_settings);
    }
}

/// SCROLL PAGING ////
var loadStatus = false;
function preload($scr, cont_id, url, success_action) {
  if(success_action == 'undefined' || success_action == undefined) var success_action = false;
  $container = $(cont_id);
  offset = $container.data('offset');
  limit = $container.data('limit');
  offset += limit;
    if($scr.scrollTop()+$scr.height() > $container.height()) {
      if(loadStatus) return false;
      $('<div></div>', {
        class: 'loading-relative',
      }).appendTo($container);
      loadStatus = true;
      $.get(url, {_offset: offset, _limit: limit}, function(response) {
        var data = $(response.data).find(cont_id).contents();
        $container.data('offset', offset);
        $container.append(data);
        loadStatus = false;
        $('.loading-relative').remove();
        if(success_action) success_action(response);
        $('[data-toggle="likers"]').popover({trigger: 'manual', container: 'body', html: true});
    }, 'json');
  }
}

function refreshContainer(url, cont_id, postData, success_action){
    if(loadStatus) return false;
    var $container = $(cont_id);
    $container.css('opacity', '0.8');
    $('<div></div>', {
      class: 'loading-fixed',
    }).appendTo($container);
    loadStatus = true;
    $.get(url, postData, function(response) {
      var data = $(response.data).find(cont_id).contents();
      $container.html(data);
      $container.css('opacity', '1');
      loadStatus = false;
      $('.loading-fixed').remove();
      if(success_action) success_action(response);
      $('[data-toggle="likers"]').popover({trigger: 'manual', container: 'body', html: true});
  }, 'json');
}

function masonryPreload($scr, cont_id, url, msnr_itm_slctr) {
  $container = $(cont_id);
  offset = $container.data('offset');
  limit = $container.data('limit');
  offset += limit;
  if($scr.scrollTop()+$scr.height() > $container.height()) {
    if(loadStatus) return false;
    loadStatus = true;
    $.get(url, {_offset: offset, _limit: limit}, function(response) {
      var data = $(response.data).find(cont_id).contents();
      $container.data('offset', offset);
      $(data).filter(msnr_itm_slctr).each(function(){
        msnry.element.appendChild(this);
        msnry.appended(this);
      });
      loadStatus = false;

      $('[data-toggle="likers"]').popover({trigger: 'manual', container: 'body', html: true});
    }, 'json');
  }
}

$(document).on('ready', function() {



  // ------------------- COMMENTS -----------------------//

  var open_comments = [];
  var load_progress = [];

  function showComments(button,cl) {
    var section = button.data('section');
    var section_id = button.data('id');
    var activity_block = button.parents('.activity-block');
    button.css('opacity', '0.2');
    loadComments(section, section_id,activity_block.find('.comments-block'),activity_block.find('.count_int'), 1, function(){button.css('opacity', '1');});
  }

  function hideComments(button, cl) {
    if(load_progress[cl] == 1) {
      console.log('loading...');
      return false;
    }
    button.parents('.activity-block').find('.comments-block').slideUp('slow');
  }

  $('body').on('click','[data-action=show-comments]', function(e){
    e.preventDefault();
    var button = $(this);
    var cl = button.data('section')+button.data('id');
    if (typeof open_comments[cl] == 'undefined' || open_comments[cl] == 0) open_comments[cl] = 1;
    else open_comments[cl] = 0;
    return (open_comments[cl] == 1) ? showComments(button,cl) : hideComments(button,cl);
  });


  // submit comment
  $('body').on('click','.comment-submit [type=submit]', function(e) {
    e.preventDefault();
    var form = $(this).parents('form');
    var form_data = form.serializeArray();
    var url = form.attr('action');
    var container = form.parents('.activity-block').find('.comments-block');
    form.css('opacity', '0.2');
    $('<div></div>', {class: 'loading-thumb'}).insertAfter(form);

    $.post(url, form_data, function(response) {
      form.parents('.activity-block').find('.count_int').html(response.extra_data.count);
      container.html(response.data);

      $('div.loading-thumb').remove();
      form.css('opacity', '1');

      if(response.success) {} 
      else notifyError(response);
    }, 'json');

  });




  /* delete comments */

  $('body').on('click','.delete_comment', function(e){
    e.preventDefault();
    var button = $(this);
    var section = button.data('section');
    var section_id = button.data('id');
    var comment_id = button.data('comment');
    var url = button.attr('href');
    button.css('opacity', '0.2');
    $.post(url, function(response) {
      button.css('opacity', '1');
      if(response.success == 1) {
        var activity_block = button.parents('.activity-block');
        loadComments(section, section_id,activity_block.find('.comments-block'), activity_block.find('.count_int'), 0);
      } else { $.notify({message: response.message },notify_error_settings);}
     
    }, 'json');

  });
  

  /* edit comments */
  var comment_edit_in_progress = 0;
  $('body').on('click', '.edit_comment', function(e){
    e.preventDefault();
    var button = $(this);
    var comment_id = button.data('comment');
    var container = button.parents('.comment-item');
    if(comment_edit_in_progress ==1) {
      console.log('edit in progress...');
      return false;
    }
    container.css('opacity', '0.2');
    comment_edit_in_progress = 1;
    $.get('/comments/edit/'+comment_id, function(response) {
      if(response.success == 1) {
        container.replaceWith(response.data);
        var new_container = $('[data-editcomment='+comment_id+']');
        new_container.on('click', '[type=submit]', function(e){
          e.preventDefault();
          var form = $(this).parents('form');
          var form_data = form.serializeArray();
          var url = form.attr('action');
          new_container.css('opacity', '0.2');
          $.post(url, form_data, function(response){
            if(response.success == 1) {
              $.get('/comments/view/'+comment_id, function(response) {
                if(response.success == 1) {
                  new_container.parent().replaceWith(response.data);
                  comment_edit_in_progress = 0;
                } else { $.notify({message: response.message },notify_error_settings);}

              }, 'json');

            } else { $.notify({message: response.message },notify_error_settings);}

          }, 'json');

       });
        
      } else { $.notify({message: response.message },notify_error_settings);}
     
    }, 'json');

  });

/*  Comments rating  */

  $('body').on('click','.comment_rating', function(e){
    e.preventDefault();
    var button = $(this);
    var section = button.data('section');
    var section_id = button.data('id');
    var link_href = button.attr('href');
    button.css('opacity', '0.2');
    $.get(link_href, function(response) {
      button.css('opacity', '1');
      if(response.success == 1) {
        var activity_block = button.parents('.activity-block');
        loadComments(section, section_id,activity_block.find('.comments-block'), activity_block.find('.count_int'), 0);
      } else { $.notify({message: response.message },notify_error_settings);}
     
    }, 'json');

  });

/* Comments pagination */

  $('body').on('click','.comments_pagination a', function(e){
    e.preventDefault();
    var button = $(this);
    var link_href = button.attr('href');
    var section = button.data('section');
    var section_id = button.data('id');
    var activity_block = button.parents('.activity-block');
    var container = activity_block.find('.comments-block');
    container.css('opacity', '0.3');

    loadComments(section, section_id, container ,activity_block.find('.count_int'), 0, function(){container.css('opacity', '1');}, link_href);

  });

  function loadComments(section, section_id, $comments_block, $count_int, effects, compl_func, url)
  {
    var cl = section+section_id;
    if(load_progress[cl] == 1) {
      console.log('loading...');
      return false;
    }
    load_progress[cl] = 1;
    
    url = (url == undefined)? '/comments/get/'+section+'/'+section_id : url;

    $.get(url, function(response) {
      if(response.success == 1) {
        if(effects == 1) $comments_block.hide();
        $comments_block.html(response.data);
        if(effects == 1) $comments_block.slideDown('slow');
        $count_int.html(response.extra_data.count);
      } 
      else {
        // $.notify({message: response.message },notify_error_settings);
        if(typeof(response.message) == 'object') {
          $.each(response.message, function(fieldset_name){
            var message = 'Fieldset: <b>'+fieldset_name+'</b> ';
            $.each(this, function(field_name){
              $.notify({message: message + '<br /> Check values for: <b>'+field_name+'</b>' },notify_warning_settings);
            });
          });
        } else {
          if(response.code == '401') $("html, body").animate({ scrollTop: $('.auth_container').offset().top }, "slow");
          $.notify({message: response.message },notify_warning_settings);
        }
      }
      load_progress[cl] = 0;
      compl_func();
      setTimeout(function(){
        initGridMaker();
      }, 100);
      
    }, 'json');
  }



  // ------------------- SUBSCRIPTION -----------------------//

  $('body').on('click', '.action_button', function(e){
      e.preventDefault();
      var $link = $(this);
      var url = $link.attr('href');
      var container_id = $link.parents('[data-xhrctr]').data('xhrctr');

      $.get(url, function(response) {
        if(response.success == 1) {
          var $button = $link.find('button');
          if(response.extra_data.status == 'subscribed') {
            $button.removeClass($button.data('acolor')).addClass($button.data('ccolor'));
            $button.html('');
            $('<i></i>', {class: $button.data('cicon')}).appendTo($button);
            $button.append($button.data('ctext'));
          } else {
            $button.removeClass($button.data('ccolor')).addClass($button.data('acolor'));
            $button.html('');
            $('<i></i>', {class: $button.data('aicon')}).appendTo($button);
            $button.append($button.data('atext'));
          }
          $.notify({message: response.message },notify_success_settings);
          var subs_count = response.extra_data.count;
          if(subs_count > 0) {
            $('[data-xhrctr='+container_id+'] .'+$button.data('cclass')).html(subs_count);
            $('[data-xhrctr='+container_id+'] .'+$button.data('cclass')+'_icon').removeClass('hide');
          } else {
            $('[data-xhrctr='+container_id+'] .'+$button.data('cclass')).html('');
            $('[data-xhrctr='+container_id+'] .'+$button.data('cclass')+'_icon').addClass('hide');
          }
          
        } else { 
          if(response.code == 401) $("html, body").animate({ scrollTop: $('.auth_container').offset().top }, "slow");
          $.notify({message: response.message },notify_warning_settings);
        }
       
      }, 'json');
  });

  // ------------------- LIKES -----------------------//


  $('body').on('click','.like_btn', function(e){
    e.preventDefault();
    var container = $(this);
    var section = container.data('section');
    var section_id = container.data('id');
    $.get('/like/'+section+'/'+section_id, function(response) {
      if(response.success == 1) {
        var count = (response.data.count == 0)? '' : response.data.count;
        container.find('.change-icon').append(container.find('.change-icon > .fa:nth-child(1)'));
        container.find('.count').html(count);
        container.attr('data-content', response.data.view);
        
        var popover = container.data('bs.popover');
        popover.setContent(response.data.view);
        popover.$tip.addClass(popover.options.placement);
      } 
      else {
        if(typeof(response.message) == 'object') {
          $.each(response.message, function(fieldset_name){
            var message = 'Fieldset: <b>'+fieldset_name+'</b> ';
            $.each(this, function(field_name){
              $.notify({message: message + '<br /> Check values for: <b>'+field_name+'</b>' },notify_warning_settings);
            });
          });
        } else {
          if(response.code == '401') $("html, body").animate({ scrollTop: $('.auth_container').offset().top }, "slow");
          $.notify({message: response.message },notify_warning_settings);
        }
      }
    }, 'json');
  });

  $('[data-toggle="likers"]').popover({trigger: 'manual', container: 'body', html: true});
  var pop_visible = 0;
  var pop_dont_hide = 0;
  $('body').on('mouseenter','[data-toggle="likers"]',function(){
    pop_dont_hide = 0;
    var popAnchor = $(this);
    if(pop_visible == 0) {
      popAnchor.popover('show');
      pop_visible = 1;
    }
    
    popAnchor.on('mouseleave', function(){
    setTimeout(function(){
        if(pop_dont_hide == 0) {
          popAnchor.popover('hide');
          pop_visible = 0;
        }
      },700);
    });

    $('body').on('mouseenter','.popover', function(){
      pop_dont_hide = 1;
      if(pop_visible == 0) {
        popAnchor.popover('show');
        pop_visible = 1;
      }
    });
    $('body').on('mouseleave','.popover', function(){
      pop_dont_hide = 0;
      setTimeout(function(){
          popAnchor.popover('hide');
          pop_visible = 0;
        },500);
    });

  });
});
