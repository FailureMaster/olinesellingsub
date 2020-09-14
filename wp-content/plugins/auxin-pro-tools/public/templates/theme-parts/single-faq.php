<?php

while ( have_posts() ) : the_post();

    auxpro_get_template_part( 'theme-parts/entry/single', 'faq');

endwhile; // end of the loop.