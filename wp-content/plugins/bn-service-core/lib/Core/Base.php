<?php
namespace BN\Core;

use BN\Core\Helpers;

class Base {
    protected $controller;

    public function __construct() {
        $this->addActions();
        $this->addFilters();
    }
    
    public function addActions() {
        add_action('admin_enqueue_scripts', array(&$this, 'adminEnqueueScripts')); 
        add_action('wp_ajax_asyncfunction', array(&$this, 'executeLibraryController'));
        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('admin_head', array($this, 'hideUpdateNotice'));
    }
    
    public function addFilters() {
        
    }

    public function adminMenu() {
        add_menu_page('Core', 'Core', 'manage_options', 'init'.Helpers::NAMESPACE, array($this, 'executeLibraryController'), 'dashicons-awards', 10);
        add_submenu_page('init'.Helpers::NAMESPACE, __('Settings', Helpers::LOCALE), __('Settings', Helpers::LOCALE), 'manage_options', 'configure'.Helpers::NAMESPACE,  array($this, 'executeAdminController'));
    }
    
    public function adminEnqueueScripts() {
        wp_register_script(Helpers::NAME.'-configure', Helpers::jsUrl( Helpers::NAME.'-configure.js' ), array('jquery'), '20170604', false);
        wp_register_script(Helpers::NAME, Helpers::jsUrl(Helpers::NAME.'.js'), array('jquery'), '20170604', false);
        wp_register_style(Helpers::NAME.'-configure', Helpers::cssUrl(Helpers::NAME.'-configure.css'), array(), '20170604');
        wp_register_style(Helpers::NAME, Helpers::cssUrl(Helpers::NAME.'.css'), array(), '20170604');
    }
    
    public function executeLibraryController() {
        $this->controller = LibraryController::getInstance();
        if (isset($_POST['action'])) {
            $this->controller->setAction($_POST['action']);
        }
        else if (isset($_GET['page'])) {
            if (strpos($_GET['page'], Helpers::NAMESPACE) !== false) {
                $action = str_replace(Helpers::NAMESPACE, "", $_GET['page']);
                $this->controller->setAction($action);
            }
        }
        else if (!isset($_GET['page']) && !isset($_GET['action'])) {
            $this->controller->setAction('init');
        }
        $this->controller->execute();
    }
    
    public function executeAdminController() {
        $this->controller = AdminController::getInstance();
        if (isset($_POST['action'])) {
            $this->controller->setAction($_POST['action']);
        }
        else if (isset($_GET['page'])) {
            if (strpos($_GET['page'], Helpers::NAMESPACE) !== false) {
                $action = str_replace(Helpers::NAMESPACE, "", $_GET['page']);
                $this->controller->setAction($action);
            }
        }
        else if (!isset($_GET['page']) && !isset($_GET['action'])) {
            $this->controller->setAction('init');
        }
        $this->controller->execute();
    }
    
    public function hideUpdateNotice(){
        if (!current_user_can('update_core')) {
            remove_action( 'admin_notices', 'update_nag', 3 );
        }
        add_action('admin_notices', array($this, 'showFormaVersion'));
    }
    
    public function showFormaVersion(){
        $version_local = trim(Helpers::getOption("core-forma-version"));
        $version_deployed = $this->getDeployedCoreFormaVersion();
        if (version_compare($version_deployed, $version_local, '>')) :
        ?>
            <div class="notice notice-warning">
                <p><?php _e('<a href="">Forma '.$version_deployed.'</a> is available! Please update now.', Helpers::LOCALE); ?></p>
            </div>
        <?php
        endif;
    }
    
    public function getDeployedCoreFormaVersion(){ 
        $response = wp_remote_get(Helpers::DEPLOYED_VERSION_FILE);
        $body = wp_remote_retrieve_body($response);
        return trim($body);
    }
}