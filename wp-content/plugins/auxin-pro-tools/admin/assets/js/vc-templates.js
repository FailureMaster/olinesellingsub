( function($) {
    
    $('.auxin-vc-template-download').on( 'click', function(e) {

        var $this = $(this),
            data = {
                action : 'auxin_vc_template_download',
                n      : $this.data('n'),
                demo   : $this.data('template-id')
            };

        $.post( ajaxurl, data, function( res ) {
            if ( res ) {
                // Insert data to the editor
                var models;
                _.each(vc.filters.templates, function(callback) {
                    res = callback(res)
                }), models = vc.storage.parseContent({}, res), _.each(models, function(model) {
                    vc.shortcodes.create(model)
                }), vc.closeActivePanel()
            }
        });
    });

})(jQuery);