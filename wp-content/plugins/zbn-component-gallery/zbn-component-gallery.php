<?php
/*
Plugin Name: BN Component GALLERY Plugin
Description: Plugin para crear galerías con un shortcode
Version: 1.0
Author: lotykun@gmail.com
Author URI: http://www.opensistemas.com/
*/

define( 'BN_GALLERY_PLUGIN_FILE', __FILE__ );
define( 'BN_GALLERY_ROOT', dirname( __FILE__ ) );
define( 'BN_GALLERY_PLUGIN_VERSION', '1.0' );

require_once( BN_GALLERY_ROOT . '/lib/Gallery/Gallery.php' );

use BN\Component\Gallery\Gallery;

$gallery = new Gallery();