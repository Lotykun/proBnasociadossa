<?php

/*
Plugin Name: BN TaxonomyType Project Status Plugin
Plugin URI: http://www.bnasociados.com/
Description: Plugin que genera una nueva taxonomia llamada Estado del Proyecto.
Version: 1.0.0
Author: Juan Lotito
Author Email: lotykun@gmail.com
Text Domain: zbn-taxonomytype-project_status
Domain Path: /languages
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

namespace BN\TaxonomyProjectStatus;
use BN\TaxonomyProjectStatus\Autoload;

define('BN_TAXONOMYPROJECTSTATUS_PLUGIN_FILE', __FILE__ );
define('BN_TAXONOMYPROJECTSTATUS_ROOT', dirname( __FILE__ ));
define('BN_TAXONOMYPROJECTSTATUS_NAMESPACE', "TaxonomyProjectStatus");
define('BN_TAXONOMYPROJECTSTATUS_TAX_NAME_SING', __('project_status', 'bn-taxonomytype-project-status'));

if (file_exists(BN_TAXONOMYPROJECTSTATUS_ROOT.'/lib/Autoload.php')) {
    require_once(BN_TAXONOMYPROJECTSTATUS_ROOT.'/lib/Autoload.php');
}
if (file_exists(BN_TAXONOMYPROJECTSTATUS_ROOT.'/vendor/autoload.php')) {
    require_once(BN_TAXONOMYPROJECTSTATUS_ROOT.'/vendor/autoload.php');
}
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$Autoloader = Autoload::getInstance(BN_TAXONOMYPROJECTSTATUS_ROOT);
$zbntaxprojectstatus = TaxonomyProjectStatus::getInstance();