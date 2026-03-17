(function ($) {
    function openMediaSelector(targetId, type) {
        var frame = wp.media({
            title: 'Seleccionar archivo',
            button: { text: 'Usar este archivo' },
            library: { type: type || undefined },
            multiple: false,
        });

        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            if (attachment && attachment.url) {
                $('#' + targetId).val(attachment.url).trigger('change');
            }
        });

        frame.open();
    }

    $(document).on('click', '.cad-media-select', function (event) {
        event.preventDefault();
        var targetId = $(this).data('media-target');
        var type = $(this).data('media-type');
        if (!targetId) {
            return;
        }
        openMediaSelector(targetId, type);
    });
})(jQuery);
