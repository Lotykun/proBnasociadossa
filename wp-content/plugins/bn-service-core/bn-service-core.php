<?php

/*
Plugin Name: BN Service Core
Plugin URI: http://www.opensistemas.com/
Description: Plugin que crga las librerias comunes de todo el CMS.
Version: 1.0.0
Author: Juan Lotito
Author Email: jlotito@opensistemas.com
Text Domain: test
License: GPLv2
*/

/* 
Copyright (C) 2015 jlotito

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

namespace BN\Core;
use BN\Core\Autoload;
use BN\Core\Core;

define('BN_CORE_PLUGIN_FILE', __FILE__ );
define('BN_CORE_ROOT', dirname( __FILE__ ));
define('BN_CORE_NAMESPACE', "Core");
define('BN_CORE_NAME', "bn-service-core");
define('BN_CORE_LOCALE', "bn-service-core");
define('BN_CORE_DEPLOYED_VERSION_FILE', "https://ueforma.blob.core.windows.net/version/forma.txt");

if (file_exists(BN_CORE_ROOT.'/lib/Autoload.php')) {
    require_once(BN_CORE_ROOT.'/lib/Autoload.php');
}
if (file_exists(BN_CORE_ROOT.'/vendor/autoload.php')) {
    require_once(BN_CORE_ROOT.'/vendor/autoload.php');
}
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$Autoloader = Autoload::getInstance(BN_CORE_ROOT);
$bncore = Core::getInstance();

register_activation_hook( __FILE__, array(Core::getInstance(), 'install'));
register_deactivation_hook(__FILE__, array(Core::getInstance(), 'deinstall'));