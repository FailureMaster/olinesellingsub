/**
 * Initialize all modules
 */
;(function($, window, document, undefined){

    $( window ).on( 'elementor:init', function() {

        // Add auxin specific css class to elementor body
        $('.elementor-editor-active').addClass('auxin-pro');
        
        // Enables the live preview for Page Cover in Elementor Editor
        function auxPageCover ( panel, model, view ) {
            view.listenTo( model.get( 'settings' ), 'change', function( changedModel ){
                if ( '' !== model.getSetting('aux_page_cover') && view.$el.hasClass('aux-page-cover-wrapper') ) {
                    view.render();
                }
            },view);
        }

        elementor.hooks.addAction( 'panel/open_editor/section', auxPageCover );
    });

})(jQuery, window, document);
