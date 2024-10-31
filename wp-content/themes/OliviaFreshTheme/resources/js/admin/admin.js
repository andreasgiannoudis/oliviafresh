jQuery(document).ready(function($) {
    window.upload_image = function(option_name) {
        var custom_uploader;
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media({
            title: 'Välj bild',
            button: {
                text: 'Använd bild'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#' + option_name).val(attachment.url);
            $('#' + option_name + '_preview').attr('src', attachment.url);
        }).open();
    }
});
