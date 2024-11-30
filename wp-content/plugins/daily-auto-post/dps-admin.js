jQuery(document).ready(function($) {
    // Media uploader for single image
    $('.upload_image_button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var custom_uploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            button.prev().val(attachment.url);
        }).open();
    });

    // Media uploader for gallery
    $('.upload_gallery_button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var custom_uploader = wp.media({
            title: 'Select Images',
            button: {
                text: 'Use these images'
            },
            multiple: true
        }).on('select', function() {
            var attachments = custom_uploader.state().get('selection').map(function(attachment) {
                attachment = attachment.toJSON();
                return attachment.url;
            });
            button.prev().val(attachments.join(', '));
        }).open();
    });
});
