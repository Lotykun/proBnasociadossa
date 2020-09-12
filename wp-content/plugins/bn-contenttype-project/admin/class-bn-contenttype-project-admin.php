<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    bn_contenttype_project
 * @subpackage bn_contenttype_project/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    bn_contenttype_project
 * @subpackage bn_contenttype_project/admin
 * @author     Your Name <email@example.com>
 */
class bn_contenttype_project_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bn_contenttype_project    The ID of this plugin.
	 */
	private $bn_contenttype_project;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $bn_contenttype_project       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bn_contenttype_project, $version ) {

		$this->bn_contenttype_project = $bn_contenttype_project;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in bn_contenttype_project_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The bn_contenttype_project_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->bn_contenttype_project, plugin_dir_url( __FILE__ ) . 'css/bn-contenttype-project-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in bn_contenttype_project_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The bn_contenttype_project_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->bn_contenttype_project, plugin_dir_url( __FILE__ ) . 'js/bn-contenttype-project-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function register_cpt(){
        $labels = array(
            'name'               => _x( 'Projects', 'bn-contenttype-project' ),
            'singular_name'      => _x( 'Project', 'bn-contenttype-project' ),
            'add_new'            => __( 'Add New','bn-contenttype-project'),
            'add_new_item'       => __( 'New Project','bn-contenttype-project'),
            'edit_item'          => __( 'Edit Project','bn-contenttype-project' ),
            'new_item'           => __( 'New Project','bn-contenttype-project' ),
            'all_items'          => __( 'All Projects','bn-contenttype-project' ),
            'view_item'          => __( 'View Project','bn-contenttype-project'),
            'search_items'       => __( 'Search Project','bn-contenttype-project'),
            'not_found'          => __( 'Not found!','bn-contenttype-project' ),
            'parent_item_colon'  => '',
        );
        $args = array(
            'labels'             => $labels,
            'show_ui'            => true,
            'publicly_queryable' => true,
            'capability_type'    => 'post',
            'can_export'         => true,
            'public' => true,
            'menu_icon' => 'dashicons-format-aside',
            'menu_position' => 6,
            'has_archive' => TRUE,
            'rewrite' => array('slug' => 'projects'),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'author'),
        );
        register_post_type( 'project', $args );
        register_taxonomy_for_object_type('category', 'project');
    }

    public function register_cpt_taxonomies(){
        $labels = array(
            'name'              => __( 'Project Status', 'bn-contenttype-project' ),
            'singular_name'     => __( 'Project Status', 'bn-contenttype-project' ),
            'search_items'      => __( 'Search Project Status', 'bn-contenttype-project' ),
            'all_items'         => __( 'All Project Status', 'bn-contenttype-project' ),
            'parent_item'       => null,
            'parent_item_colon' => null,
            'edit_item'         => __( 'Edit Project Status', 'bn-contenttype-project' ),
            'update_item'       => __( 'Update Project Status', 'bn-contenttype-project' ),
            'add_new_item'      => __( 'Add New Project Status', 'bn-contenttype-project' ),
            'new_item_name'     => __( 'New Project Status Name', 'bn-contenttype-project' ),
            'menu_name'         => __( 'Project Status', 'bn-contenttype-project' ),
            'not_found'         => __( 'No Project Status found', 'bn-contenttype-project' ),
        );

        $args = array(
            'hierarchical'      => false,
            'label'             => __('Project Status','bn-contenttype-project'),
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'update_count_callback' => '_update_post_term_count',
            'show_admin_column' => true,
        );

        register_taxonomy(__('project_status', 'bn-contenttype-project'), array(0 => 'project'), $args);

        $labels = array(
            'name'              => __( 'Years', 'bn-contenttype-project' ),
            'singular_name'     => __( 'Year', 'bn-contenttype-project' ),
            'search_items'      => __( 'Search Years', 'bn-contenttype-project' ),
            'all_items'         => __( 'All Years', 'bn-contenttype-project' ),
            'parent_item'       => null,
            'parent_item_colon' => null,
            'edit_item'         => __( 'Edit Year', 'bn-contenttype-project' ),
            'update_item'       => __( 'Update Year', 'bn-contenttype-project' ),
            'add_new_item'      => __( 'Add New Year', 'bn-contenttype-project' ),
            'new_item_name'     => __( 'New Year Name', 'bn-contenttype-project' ),
            'menu_name'         => __( 'Years', 'bn-contenttype-project' ),
            'not_found'         => __( 'No Year found', 'bn-contenttype-project' ),
        );

        $args = array(
            'hierarchical'      => false,
            'label'             => __('Year','bn-contenttype-project'),
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'update_count_callback' => '_update_post_term_count',
            'show_admin_column' => true,
        );

        register_taxonomy(__('year', 'bn-contenttype-project'), array(0 => 'project'), $args );
    }

    public function register_fields_metaboxs() {
	    $loty = '';
        add_meta_box(
            'promotor_mb',
            __('Promotor','bn-contenttype-project'),
            array(&$this,'promotor_field_metabox'),
            null,
            'side',
            'high'
        );
        add_meta_box(
            'surface_mb',
            __('Superficie','bn-contenttype-project'),
            array(&$this,'surface_field_metabox'),
            null,
            'side',
            'high'
        );
        add_meta_box(
            'budget_mb',
            __('Presupuesto','bn-contenttype-project'),
            array(&$this,'budget_field_metabox'),
            null,
            'side',
            'high'
        );
        add_meta_box(
            'featuredgallery_mb',
            __('Featured Gallery','bn-contenttype-project'),
            array(&$this,'featuredgallery_field_metabox'),
            null,
            'side',
            'high'
        );
    }

    public function render_embed_external() {
        $data = array();
        $shortcode = $_POST["shortcode"];

        $data['success'] = (isset($shortcode) && !empty($shortcode)) ? TRUE : FALSE;
        $data['html'] = do_shortcode(stripslashes($shortcode));
        wp_send_json($data);
    }

    public function featured_gallery_enqueue_scripts() {
        wp_register_script(
            'bn-contenttype-project-featuredgallery-metabox',
            plugin_dir_url( __FILE__ ) . 'js/bn-contenttype-project-featuredgallery-metabox.js',
            array('jquery'),
            '20170604',
            false
        );


        /*wp_enqueue_script(
            $this->bn_contenttype_project,
            plugin_dir_url( __FILE__ ) . 'js/bn-contenttype-project-featuredgallery-metabox.js',
            array( 'jquery' ),
            $this->version,
            false
        );*/
    }

    public function save_fields_metaboxs($post_id) {
        if( !isset( $_POST['promotor_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['promotor_meta_box_nonce'], 'promotor_mb_nonce' ) ) return;
        if( !isset( $_POST['surface_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['surface_meta_box_nonce'], 'surface_mb_nonce' ) ) return;
        if( !isset( $_POST['budget_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['budget_meta_box_nonce'], 'budget_mb_nonce' ) ) return;
        if( !isset( $_POST['featuredgallery_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['featuredgallery_meta_box_nonce'], 'featuredgallery_mb_nonce' ) ) return;

        // Comprobamos si el usuario actual no puede editar el post
        if( !current_user_can( 'edit_post' ) ) return;

        // Guardamos...
        if( isset( $_POST['promotor'] ) )
            update_post_meta( $post_id, 'promotor', $_POST['promotor'] );
        if( isset( $_POST['surface'] ) )
            update_post_meta( $post_id, 'surface', $_POST['surface'] );
        if( isset( $_POST['budget'] ) )
            update_post_meta( $post_id, 'budget', $_POST['budget'] );
        if( isset( $_POST['featuredgallery'] ) )
            update_post_meta( $post_id, 'featuredgallery', $_POST['featuredgallery'] );
    }

    public function promotor_field_metabox($post) {

        $data = get_post_meta( $post->ID, 'promotor', true );
        wp_nonce_field( 'promotor_mb_nonce', 'promotor_meta_box_nonce' );

        echo '<input type="text" name="promotor" value="' . $data .'" style="width:100%"/>';
    }

    public function surface_field_metabox($post) {

        $data = get_post_meta( $post->ID, 'surface', true );
        wp_nonce_field( 'surface_mb_nonce', 'surface_meta_box_nonce' );

        echo '<input type="text" name="surface" value="' . $data .'" style="width:100%"/>';
    }

    public function budget_field_metabox($post) {

        $data = get_post_meta( $post->ID, 'budget', true );
        wp_nonce_field( 'budget_mb_nonce', 'budget_meta_box_nonce' );

        echo '<input type="text" name="budget" value="' . $data . '" style="width:100%"/>';
    }

    public function featuredgallery_field_metabox($post) {

        $tab_default = __('Create Gallery');
        $tabs_to_show = array(
            __('Create Gallery')
        );
        $messages = array(
            "removeattachment" => __('Remove Featured Gallery','bn-contenttype-project'),
            "setattachment" => __('Set Featured Gallery', 'bn-contenttype-project')
        );
        wp_localize_script('bn-contenttype-project-featuredgallery-metabox', 'tabdefault', $tab_default);
        wp_localize_script('bn-contenttype-project-featuredgallery-metabox', 'tabstoshow', $tabs_to_show);
        wp_localize_script('bn-contenttype-project-featuredgallery-metabox', 'messages', $messages);

        wp_enqueue_script('bn-contenttype-project-featuredgallery-metabox');
        $data = get_post_meta( $post->ID, 'featuredgallery', true );
        wp_nonce_field( 'featuredgallery_mb_nonce', 'featuredgallery_meta_box_nonce' );

        if (isset($data) && !empty($data)){
            $outputHtml = do_shortcode($data);
            $outputHtml .= '<a href="#" id="remove-post-featuredgallery">Remove Featured Gallery</a>';
        } else {
            $outputHtml = '<p class="hide-if-no-js">
                <a href="#" id="set-post-featuredgallery">Set Featured Gallery</a>
            </p>';
        }

        echo $outputHtml . '<input type="hidden" name="featuredgallery" id="featuredgallery" value="' . $data . '"/>';
    }
}
