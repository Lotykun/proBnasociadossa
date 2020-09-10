<?php

/*
Plugin Name: BN ContentType PROJECT Plugin
Plugin URI: http://www.opensistemas.com/
Description: Plugin que genera el CPT PROJECT y todos los campos asociados a el.
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

namespace BN\ContentProject;
use BN\ContentProject\Autoload;

define('BN_CONTENTPROJECT_PLUGIN_FILE', __FILE__ );
define('BN_CONTENTPROJECT_ROOT', dirname( __FILE__ ));
define('BN_CONTENTPROJECT_NAMESPACE', "ContentProject");
define('BN_CONTENTPROJECT_NAME', "zbn-contenttype-project");
define('BN_CONTENTPROJECT_LOCALE', "zbn-contenttype-project");
define('BN_CONTENTPROJECT_CPT_NAME_SING', "project");
define('BN_CONTENTPROJECT_CPT_NAME_PLU', "projects");

if (file_exists(BN_CONTENTPROJECT_ROOT.'/lib/Autoload.php')) {
    require_once(BN_CONTENTPROJECT_ROOT.'/lib/Autoload.php');
}
if (file_exists(BN_CONTENTPROJECT_ROOT.'/vendor/autoload.php')) {
    require_once(BN_CONTENTPROJECT_ROOT.'/vendor/autoload.php');
}
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$Autoloader = Autoload::getInstance(BN_CONTENTPROJECT_ROOT);
$zbncontentproject = ContentProject::getInstance();
