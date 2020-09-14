<?php 
/**
 * Add your custom php code below. 
 * 
 * We recommend you to use "code-snippets" plugin instead: https://wordpress.org/plugins/code-snippets/
 **/

function custom_login_logo() {
?>
<style type="text/css">
body.login div#login h1 a {
background-image: url('https://onlineselling.com.ph/wp-content/uploads/2020/07/responsive.png'); 
padding-bottom: 30px;
background-size: 175px;
width: 175px;
}
</style>
<?php
} add_action( 'login_enqueue_scripts', 'custom_login_logo' );
