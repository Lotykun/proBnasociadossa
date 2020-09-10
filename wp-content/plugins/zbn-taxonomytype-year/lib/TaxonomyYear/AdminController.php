<?php
namespace BN\TaxonomyYear;

use BN\TaxonomyYear\Helpers;
class AdminController extends BaseController {
    static $instance = null;
        
    static function & getInstance() {
        if (null == AdminController::$instance) {
            AdminController::$instance = new AdminController();
        }
        return AdminController::$instance;
    }
    
    protected function allowedActions() {
        return array(
            'populate',
        );
    }
    
    public function execute() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Access denied' );
        }
        parent::execute();
    }
}