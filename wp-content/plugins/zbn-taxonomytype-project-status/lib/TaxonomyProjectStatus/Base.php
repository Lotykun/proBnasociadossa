<?php
namespace BN\TaxonomyProjectStatus;

use BN\TaxonomyProjectStatus\Helpers;
class Base {
    protected $controller;

    public function __construct() {
        $this->addActions();
        $this->addFilters();
    }
    
    public function addActions() {
        add_action('admin_enqueue_scripts', array(&$this, 'adminEnqueueScripts')); 
        add_action('init',array(&$this,'executeLibraryController'),100);
        add_action( 'plugins_loaded', array($this, 'load_plugin_textdomain'));
    }
    
    public function addFilters() {
        add_filter('pre_insert_term', array(&$this,'disallow_insert_term'), 10, 2);
    }
    
    public function adminEnqueueScripts() {
        wp_register_script('bn-taxonomytype-status', Helpers::jsUrl('zbn-taxonomytype-project-status.js'), array('jquery'), '20170604', false);
        wp_register_style('bn-taxonomytype-status', Helpers::cssUrl('zbn-taxonomytype-project-status.css'), array(), '20170604');
    }
    
    public function executeLibraryController() {
        $this->controller = LibraryController::getInstance();
        if (current_action() == "init"){
            $action = "init";
        } else if (isset($_POST['action'])) {
            $action = $_POST['action'];
        } else if (isset($_GET['page'])) {
            if (strpos($_GET['page'], BN_TAXONOMYPROJECTSTATUS_NAMESPACE) !== false) {
                $action = str_replace(BN_TAXONOMYPROJECTSTATUS_NAMESPACE, "", $_GET['page']);
            }
        } else if (!isset($_GET['page']) && !isset($_GET['action'])) {
            $action = "init";
        }
        $this->controller->setAction($action);
        $this->controller->execute();
    }
    
    public function executeAdminController() {
        $this->controller = AdminController::getInstance();
        if (current_action() == "init"){
            $action = "init";
        } else if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] != "-1") {
            $action = $_POST['action'];
        } else if (isset($_GET['page'])) {
            if (strpos($_GET['page'], BN_TAXONOMYPROJECTSTATUS_NAMESPACE) !== false) {
                $action = str_replace(BN_TAXONOMYPROJECTSTATUS_NAMESPACE, "", $_GET['page']);
            }
        } else if (!isset($_GET['page']) && !isset($_GET['action'])) {
            $action = "init";
        }
        $this->controller->setAction($action);
        $this->controller->execute();
    }
    
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'bn-taxonomytype-status', FALSE, '/zbn-taxonomytype-status/languages/' );
    }
    
    public function disallow_insert_term($term, $taxonomy) {
        $user = wp_get_current_user();
        $disable_roles = array('editor');
        if(array_intersect($disable_roles, $user->roles) && $taxonomy === 'status') {
            return new \WP_Error(
                'disallow_insert_term', 
                __('Your role does not have permission to add terms to this taxonomy')
            );
        }
        return $term;
    }
}