jQuery(document).ready(function(){
  jQuery('#child-settings\\[wpz_logo_button\\]').click(function() {
  wp.media.editor.send.attachment = function(props, attachment){
    jQuery('#child-settings\\[wpz_logo\\]').val(attachment.url);
  }
  wp.media.editor.open(this);

  return false;
  });
});