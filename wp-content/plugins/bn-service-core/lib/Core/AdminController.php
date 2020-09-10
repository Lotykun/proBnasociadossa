<?php
namespace BN\Core;

use BN\Core\Helpers;
class AdminController extends BaseController {
    static $instance = null;
        
    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    protected function allowedActions() {
        return array(
            'configure',
        );
    }
    public function execute() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Access denied' );
        }
        parent::execute();
    }
    public function configureAction() {
        $params = $this->getParams();
        echo $this->renderView( 'admin/configure.twig', $params );
    }
}