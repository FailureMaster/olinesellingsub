<?php global $post, $aux_content_width;

    $post_vars               = auxin_get_post_format_media( $post, array( 'request_from' => 'single' ) );
    extract( $post_vars );

    // Get the alignment of the title in page content
    $title_alignment         = auxin_get_post_meta( $post, 'page_content_title_alignment', 'default' );
    $title_alignment         = 'default' == $title_alignment ? '' : 'aux-text-align-' .$title_alignment;

    $post_content_style      = auxin_get_post_meta( $post, 'faq_content_style', 'default' );
?>
    <article <?php post_class(  ); ?> >

            <?php if ( $has_attach ) : ?>
            <div class="entry-media">
                <?php echo $the_media; ?>
            </div>
            <?php endif; ?>

            <div class="entry-main">
                <header class="entry-header <?php echo esc_attr( $title_alignment ); ?>">
                <?php
                if( $show_title ) { ?>
                    <h3 class="entry-title toggle-header">
                        <?php
                        $post_title = !empty( $the_name ) ? esc_html( $the_name ) : get_the_title();

                        if( ! empty( $the_link ) ){
                            echo '<cite><a href="'.esc_url( $the_link ).'" title="'.esc_attr( $post_title ).'">'.$post_title.'</a></cite>';
                        } else {
                            echo $post_title;
                        }
                        ?>
                    </h3>
                <?php
                } ?>
                </header>
                <div class="entry-content acc-content-wrap">
                    <?php
                    the_content( __( 'Continue reading', PLUGIN_DOMAIN ) );
                    // clear the floated elements at the end of content
                    echo '<div class="clear"></div>';
                    // create pagination for page content
                    wp_link_pages( array( 'before' => '<div class="page-links"><span>' . esc_html__( 'Pages:', PLUGIN_DOMAIN ) .'</span>', 'after' => '</div>' ) );
                    ?>
                </div>
            </div>


            <?php // get related posts


            if( function_exists( 'rp4wp_children' ) ){
                echo '<div class="aux-related-posts-container">' . rp4wp_children( false, false ) . '</div>';
            }
            ?>


            <?php if( auxin_get_option( 'show_faq_author_section', 1 ) ) { ?>
            <div class="entry-author-info">
                    <div class="author-avatar">
                        <?php echo get_avatar( get_the_author_meta( "user_email" ), 100 ); ?>
                    </div><!-- #author-avatar -->
                    <div class="author-description">
                        <dl>
                            <dt>
                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php echo esc_attr( sprintf( __( 'View all posts by %s', PLUGIN_DOMAIN ), get_the_author() ) ); ?>" >
                                    <?php the_author(); ?>
                                </a>
                            </dt>
                            <dd>
                            <?php if( get_the_author_meta('skills') ) { ?>
                                <span><?php the_author_meta('skills');?></span>
                            <?php }
                            if( auxin_get_option( 'show_faq_author_section_text' ) && ( get_the_author_meta('user_description') ) ) {
                                ?>
                                <p><?php the_author_meta('user_description');?>.</p>
                                <?php } ?>
                            </dd>
                        </dl>
                    </div><!-- #author-description -->

            </div> <!-- #entry-author-info -->
            <?php } ?>

       </article>