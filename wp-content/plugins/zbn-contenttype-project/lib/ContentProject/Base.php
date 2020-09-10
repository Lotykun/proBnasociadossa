<?php
namespace BN\ContentProject;

use BN\ContentProject\Helpers;

class Base {
    protected $controller;

    public function __construct() {
        $this->addActions();
        $this->addFilters();
    }

    public function addActions() {
        add_action('admin_enqueue_scripts', array(&$this, 'adminEnqueueScripts'));
        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('init',array(&$this,'executeLibraryController'));
        add_action('add_meta_boxes', array(&$this,'register_fields_metaboxes'));
        add_action('save_post_project', array(&$this,'save_metadata'));
        add_action('pre_get_posts', array(&$this,'include_noname_query'));
        //add_action('admin_menu', array($this, 'hide_options'));
        add_action('enqueue_block_editor_assets', array(&$this, 'enqueue_custom_scripts'));
    }

    public function addFilters() {
        add_filter('post_type_link', array(&$this,'custom_post_link'), 10, 2);
    }

    public function adminMenu() {
        add_submenu_page(
            'edit.php?post_type='.Helpers::CPT_NAME_SING,
            __('Settings', Helpers::LOCALE),
            __('Settings', Helpers::LOCALE),
            'manage_options',
            'configure'.Helpers::NAMESPACE,
            array($this, 'executeAdminController')
        );
    }

    public function adminEnqueueScripts() {
        wp_register_script(Helpers::NAME.'-configure', Helpers::jsUrl( Helpers::NAME.'-configure.js' ), array('jquery'), '20170604', false);
        wp_register_style(Helpers::NAME.'-configure', Helpers::cssUrl(Helpers::NAME.'-configure.css'), array(), '20170604');
        wp_register_style(Helpers::LOCALE, Helpers::cssUrl(Helpers::NAME.'.css'), array(), '20170604');

        /*wp_localize_script(Helpers::LOCALE, 'fields', Helpers::getOption(Helpers::NAMESPACE."_fields"));
        wp_localize_script(Helpers::LOCALE, 'ajaxurl', Helpers::ajaxUrl());
        wp_enqueue_script(Helpers::LOCALE);
        wp_enqueue_style(Helpers::LOCALE);*/

        wp_enqueue_script('jquery-validate-min','https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js', array('jquery'));
    }

    public function executeLibraryController() {
        $this->controller = LibraryController::getInstance();
        if (current_action() == "init"){
            $action = "init";
        } else if (isset($_POST['action'])) {
            $action = $_POST['action'];
        } else if (isset($_GET['page'])) {
            if (strpos($_GET['page'], Helpers::NAMESPACE) !== false) {
                $action = str_replace(Helpers::NAMESPACE, "", $_GET['page']);
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
        } else if (isset($_POST['action'])) {
            $action = $_POST['action'];
        } else if (isset($_GET['page'])) {
            if (strpos($_GET['page'], Helpers::NAMESPACE) !== false) {
                $action = str_replace(Helpers::NAMESPACE, "", $_GET['page']);
            }
        } else if (!isset($_GET['page']) && !isset($_GET['action'])) {
            $action = "init";
        }
        $this->controller->setAction($action);
        $this->controller->execute();
    }

    public function register_fields_metaboxes($post_type) {
        if (in_array($post_type, array("project"))) {
            $_POST['action'] = "registerfieldsmetaboxes";
            $this->executeLibraryController();
        }
    }

    public function save_metadata($post_id) {
        $post = get_post($post_id);
        if (in_array($post->post_type, array("project"))) {
            $_POST['action'] = "saveprojectmetadata";
            $_POST['postId'] = $post_id;
            $this->executeLibraryController();
        }
    }

    public function hide_options() {
        global $submenu;
        $user = wp_get_current_user();
        $allowed_roles = array('administrator', 'wpseo_editor');
        if(!array_intersect($allowed_roles, $user->roles)) {
            
        }
    }

    public function custom_post_link($post_link, $post){
        if (in_array($post->post_type, array('project')) && 'publish' == $post->post_status) {
            $SLUGG = $post->post_name;
            $post_cats = wp_get_post_categories($post->ID,array("fields" => "all"));
            if (!empty($post_cats[0])){ $target_CAT= $post_cats[0];
                while(!empty($target_CAT->slug)){
                    $SLUGG =  $target_CAT->slug .'/'.$SLUGG;
                    if  (!empty($target_CAT->parent)) {$target_CAT = get_term( $target_CAT->parent, 'category');}   else {break;}
                }
                $post_link= get_option('home').'/'. urldecode($SLUGG);
            }
        }
        return  $post_link;
    }

    public function include_noname_query($q) {

        if ($q->is_main_query() && !is_admin()){
            $query_var_post_type = $q->get('post_type');
            if (!isset($query_var_post_type) || empty($query_var_post_type)) {
                $q->set( 'post_type',  array_merge(array('post'),array('page'), array('project')));
            } else {
                if (!is_array($query_var_post_type)) {
                    $query_var_post_type = array($query_var_post_type);
                }
                if (!in_array('project', $query_var_post_type)){
                    $q->set( 'post_type',  array_merge($query_var_post_type, array('project')));
                }
            }
        }
        return $q;
    }
    
    public function enqueue_custom_scripts ($hook) {
        global $post;
        $user = wp_get_current_user();
        $disable_roles = array('editor');
        /*if(array_intersect($disable_roles, $user->roles ) ) {
            if ($post && in_array($post->post_type, array('project')) && in_array($hook, array('post.php', 'post-new.php'))){
                wp_enqueue_script('costum-post-js', Helpers::jsUrl( 'uedm-contenttype-post-hidetag.js' ), array('jquery'), '20171904');
            }
        }*/
        if ($post && in_array($post->post_type, array('project'))){
            $literals = Helpers::getOption(BN_CONTENTPROJECT_NAMESPACE."_validationliterals");
            wp_register_script('validation-project-js', Helpers::jsUrl( 'zbn-contenttype-project-validation.js' ), array(), '20170604', false);
            wp_localize_script('validation-project-js', 'validationLiterals', $literals);
            wp_localize_script('validation-project-js', 'validationRoles', $disable_roles);
            wp_localize_script('validation-project-js', 'validationUserRoles', $user->roles);
            wp_enqueue_script('validation-project-js');
        }
    }
}