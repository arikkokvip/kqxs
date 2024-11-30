jQuery(document).ready(function($){
    var mediaUploader;
    
    $('#kira_select_banner_image').on('click', function(e) {
        e.preventDefault();
        
        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        
        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on('select', function() {
            attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#kira_banner_image').val(attachment.url);
            $('#kira_banner_image_preview').html('<img src="' + attachment.url + '" style="max-width:200px;height:auto;" />');
        });
        
        // Open the uploader dialog
        mediaUploader.open();
    });
});
