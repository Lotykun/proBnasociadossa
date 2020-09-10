<?php
namespace BN\TaxonomyProjectStatus;

use BN\TaxonomyProjectStatus\Helpers;
class LibraryController extends BaseController {
    static $instance = null;

    static function & getInstance() {
        if (null == LibraryController::$instance) {
            LibraryController::$instance = new LibraryController();
        }
        return LibraryController::$instance;
    }
    
    protected function allowedActions() {
        return array(
            'init',
        );
    }
    
    public function execute() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) && $this->action !== "init") {
            wp_die( 'Access denied' );
        }
        parent::execute();
    }
    
    public function initAction() {
        $this->register_taxonomy();
    }
    
    private function register_taxonomy(){
        $labels = array(
            'name'              => __( 'Project Status', 'bn-taxonomytype-project-status' ),
            'singular_name'     => __( 'Project Status', 'bn-taxonomytype-project-status' ),
            'search_items'      => __( 'Search Project Status', 'bn-taxonomytype-project-status' ),
            'all_items'         => __( 'All Project Status', 'bn-taxonomytype-project-status' ),
            'parent_item'       => __( 'Parent Project Status', 'bn-taxonomytype-project-status' ),
            'parent_item_colon' => __( 'Parent Project Status:', 'bn-taxonomytype-project-status' ),
            'edit_item'         => __( 'Edit Project Status', 'bn-taxonomytype-project-status' ),
            'update_item'       => __( 'Update Project Status', 'bn-taxonomytype-project-status' ),
            'add_new_item'      => __( 'Add New Project Status', 'bn-taxonomytype-project-status' ),
            'new_item_name'     => __( 'New Project Status Name', 'bn-taxonomytype-project-status' ),
            'menu_name'         => __( 'Project Status', 'bn-taxonomytype-project-status' ),
            'not_found'         => __( 'No Project Status found', 'bn-taxonomytype-project-status' ),
	);

	$args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rest_base'         => BN_TAXONOMYPROJECTSTATUS_TAX_NAME_SING,
            'rewrite'           => false,
	);

	register_taxonomy( BN_TAXONOMYPROJECTSTATUS_TAX_NAME_SING, array('project'), $args );
    }
}