<?php
namespace BN\TaxonomyYear;

use BN\TaxonomyYear\Helpers;
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
            'name'              => __( 'Years', 'bn-taxonomytype-year' ),
            'singular_name'     => __( 'Year', 'bn-taxonomytype-year' ),
            'search_items'      => __( 'Search Years', 'bn-taxonomytype-year' ),
            'all_items'         => __( 'All Years', 'bn-taxonomytype-year' ),
            'parent_item'       => __( 'Parent Year', 'bn-taxonomytype-year' ),
            'parent_item_colon' => __( 'Parent Year:', 'bn-taxonomytype-year' ),
            'edit_item'         => __( 'Edit Year', 'bn-taxonomytype-year' ),
            'update_item'       => __( 'Update Year', 'bn-taxonomytype-year' ),
            'add_new_item'      => __( 'Add New Year', 'bn-taxonomytype-year' ),
            'new_item_name'     => __( 'New Year Name', 'bn-taxonomytype-year' ),
            'menu_name'         => __( 'Years', 'bn-taxonomytype-year' ),
            'not_found'         => __( 'No Year found', 'bn-taxonomytype-year' ),
	);

	$args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => false,
	);

	register_taxonomy( BN_TAXONOMYYEAR_TAX_NAME_SING, array('project'), $args );
    }
}