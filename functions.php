<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'chld_thm_cfg_parent','twentyseventeen-style' ));
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css' );
if (!function_exists('child_theme_font_css')):
    function child_theme_font_css(){
        wp_enqueue_style('child_theme_font', trailingslashit( get_stylesheet_directory_uri() ).'assets/css/fonts.css', array('chld_thm_cfg_separate','chld_thm_cfg_parent','twentyseventeen-style' ));
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_font_css' );

if (!function_exists('add_google_fonts')):
    function add_google_fonts(){
        global $twentyseventeen_childfonts;
        ?>
    <link rel="stylesheet" href=""https://fonts.googleapis.com/css?family=<?php echo join('|', $twentyseventeen_childfonts);?>" ">

    <?php
    }

endif;
function childtheme_front_page_sections() {
    return 6;
}
add_filter( 'twentyseventeen_front_page_sections', 'childtheme_front_page_sections' );
require (get_stylesheet_directory(). '/inc/customizer.php' );

// END ENQUEUE PARENT ACTION
/**
 * Displays message in javascript console
 * 
 * @param string $message 
 * @param string|int $type How the message is displayed in the console {@example 'log'} or {@example 1}.
 */
function print_to_javascript_console($message, $type = 'log'){
    echo '<script type="text/javascript>console.'.javascriptConsoleTypeVerification($type).'('.$message.')</script>"';
}
/**
 * validates or converst $type to work with the JavaScript Console
 * 
 * @param string|int $type 
 * @return string valid string that can be used with the javascript console
 */
function javascriptConsoleTypeVerification($type){
    if (!is_numeric($type)) {
        $consoleOptions = array('assert','clear','count','dir','dirxml','error','group','groupCollapsed','groupEnd','info','log','profile','profileEnd','table','time','timeEnd','timeStamp','trace','warn');
        $t = strtolower($type);
        return in_array($t , $consoleOptions)? $t: 'log';
    }
    switch ($type) {
            case 2:
                $retValue = 'info';
                break;
            case 3:
                $retValue = 'warn';
                break;
            case 4:
                $retValue = 'error';
                break;
            default:
                $retValue = 'log';
                break;
        }
    return $retValue;
}