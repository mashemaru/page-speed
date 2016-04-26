<?php
/* Andrew
========================================================================== */
function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function script_tag_async($tag, $handle) {
    if (is_admin()){
        return $tag;
    }
    if (strpos($tag, '/wp-includes/js/jquery/')) {
        return $tag;
    }
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') !==false) {
    return $tag;
    }
    if (strpos($tag, '//maps.googleapis.com/maps/api/js') !== false) {
	return $tag;
	}
    else {
        return str_replace(' src',' async src', $tag);
    }
}
//add_filter('script_loader_tag', 'script_tag_async',10,2);

function add_load_css(){ 
    ?><script><?php 
    readfile(get_stylesheet_directory() . '/js/loadCSS.js'); 
    ?></script><?php
}

function link_to_loadCSS_script($html, $handle, $href ) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $a = $dom->getElementById($handle.'-css');
    return "<script>loadCSS('" . $a->getAttribute('href') . "',0,'" . $a->getAttribute('media') . "');</script>
    <noscript><link rel='stylesheet' href='". $a->getAttribute('href') ."'></noscript>\n";
}//Added noscript markup para sa browsers na wlang JS

//Disable loadCSS in login/admin page /9999
if (!is_admin() && !is_login_page()) {
	add_action('wp_head','add_load_css',7);
	add_filter('style_loader_tag', 'link_to_loadCSS_script',10,3);
}

function custom_scripts() {
	wp_enqueue_style('custom-style', get_stylesheet_directory_uri() .'/custom-style.css' );
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
	wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css?family=Marcellus' );
}
add_action('wp_enqueue_scripts', 'custom_scripts', '15'); // set 15 para mgload ung style before custom

/* END
========================================================================== */

function crunchify_print_scripts_styles() {
    // Print all loaded Scripts
    global $wp_scripts;
    foreach( $wp_scripts->queue as $script ) :
        echo $script . '  **  ';
    endforeach;
 
    // Print all loaded Styles (CSS)
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) :
        echo $style . '  ||  ';
    endforeach;
}
add_action( 'wp_print_scripts', 'crunchify_print_scripts_styles' );