<?php // admin related functions

function auxpro_vc_get_remote_templates() {

    $res = wp_remote_get( 'http://api.averta.net/projects/phlox/vc-templates' );

    if ( ! is_wp_error( $res ) && 200 === wp_remote_retrieve_response_code( $res ) ) {
        return json_decode( wp_remote_retrieve_body( $res ), true );
    }

    return false;

}


function auxpro_vc_templates_tab( $data ){

    $new_category = array(
        'category'        => 'auxin_templates',
        'category_name'   => sprintf( __( '%s Templates', PLUGIN_DOMAIN ), THEME_NAME_I18N ),
        'category_weight' => 10,
        'templates'       => auxpro_vc_get_remote_templates()
    );

    $data[] = $new_category;

    return $data;
}


function auxpro_vc_templates_content( $category ){

    if ( 'auxin_templates' === $category['category'] ) { ?>

        <div class="vc_col-md-12 aux-vc-templates-library">
            <h3 class="vc_ui-panel-title"><?php _e( 'Phlox Templates' ); ?></h3>
            <?php if ( isset( $category['category_description'] ) ) : ?>
                <p class="vc_description"><?php esc_html_e( $category['category_description'] ); ?></p>;
            <?php endif; ?>
            <div class="vc_ui-search-box vc_ui-panel-search-box">
                <div class="vc_ui-search-box-input vc_ui-panel-search">
                    <input type="search" id="vc_template_lib_name_filter" data-vc-template-lib-name-filter=""
                        placeholder="<?php _e( 'Search template by name', 'js_composer' ); ?>">
                    <label for="vc_template_lib_name_filter"><i class="vc-composer-icon vc-c-icon-search"></i></label>
                </div>
            </div>

            <div class="vc_ui-panel-template-grid" id="vc_template-library-template-grid">

            <?php

            $local_templates = get_option( 'auxin_vc_templates', array() );
            $local_template_keys = array_keys( $local_templates );

            if ( ! empty( $category['templates'] ) ) {
                foreach ( $category['templates'] as $template ) {
                    ?>
                    <div class="vc_ui-panel-template-item vc_ui-visible" data-template-id="<?php echo $template['id']; ?>">
                        <span class="vc_ui-panel-template-item-content">
                            <img src="<?php echo $template['thumbnailUrl']; ?>" alt="">
                            <span class="vc_ui-panel-template-item-overlay">
                                <a href="<?php echo $template['previewUrl']; ?>" class="vc_ui-panel-template-item-overlay-button vc_ui-panel-template-preview-button" data-preview-url="<?php echo $template['previewUrl']; ?>" data-title="About Page With Features" data-template-id="<?php echo $template['id']; ?>" data-template-version="<?php echo $template['version']; ?>"><i class="vc-composer-icon vc-c-icon-search"></i></a>
                                <?php
                                if ( empty( $local_template_keys ) || ! in_array( $template['id'] , $local_template_keys )
                                    || intval( $local_templates[$template['id']]['version'] ) < intval( $template['version'] ) ) :
                                    ?>
                                <a href="javascript:;" target="_blank" class="vc_ui-panel-template-item-overlay-button vc_ui-panel-template-download-button auxin-vc-template-download" data-n="<?php echo wp_create_nonce( 'auxin_vc_template_download' ); ?>" data-template-id="<?php echo $template['id']; ?>">
                                <i class="vc-composer-icon vc-c-icon-arrow_downward"></i>
                                </a>
                                <?php endif; ?>
                            </span>
                        </span>
                        <span class="vc_ui-panel-template-item-name">
                            <span><?php echo $template['title']; ?></span>
                        </span>
                    </div>
                    <?php
                }
            }
            ?>
            </div>

        </div>
    <?php
    }

    return $category;

}
