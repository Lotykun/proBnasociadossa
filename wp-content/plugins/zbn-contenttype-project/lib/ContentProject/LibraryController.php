<?php
namespace BN\ContentProject;

use BN\ContentProject\Helpers;

class LibraryController extends BaseController {
    static $instance = null;

    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function allowedActions() {
        return array(
            'init',
            'registerfieldsmetaboxes',
            'saveprojectmetadata'
        );
    }

    public function execute() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) && $this->action !== "init") {
            wp_die( 'Access denied' );
        }
        parent::execute();
    }

    public function initAction() {
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");
        $this->register_cpt();
        if (get_current_user_id()){
            $this->set_user_metaboxes();
        }

        if(!empty($fields["extra"])) {
            foreach ($fields["extra"] as $fieldkey => $fieldvalue) {
                $fieldObject = Field\Factory::get_field_instance($fieldkey);
                if (!empty($fieldvalue['required']) && !empty($fieldvalue['enabled'])) {
                    $fieldObject->init();
                }
            }
        }
    }

    public function registerfieldsmetaboxesAction() {
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");

        if(!empty($fields["extra"])) {
            foreach ($fields["extra"] as $fieldkey => $fieldvalue) {
                if (!empty($fieldvalue['required']) && !empty($fieldvalue['enabled']) && isset($fieldvalue['metabox'])){
                    $fieldObject = Field\Factory::get_field_instance($fieldkey);
                    $fieldObject->register_field_metabox();
                }
            }
        }
    }

    public function saveprojectmetadataAction() {
        $post_id = Helpers::getRequestPostParam("postId");
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");

        if(!empty($fields["extra"])) {
            foreach ($fields["extra"] as $fieldkey => $fieldvalue) {
                if ($fieldvalue['metabox']) {
                    $fieldObject = Field\Factory::get_field_instance($fieldkey);
                    $fieldObject->save_field_metadata($post_id);
                }
            }
        }
    }

    private function register_cpt(){
        $args = array(
            'public' => true,
            'label'  => __(ucfirst(Helpers::CPT_NAME_PLU), Helpers::LOCALE),
            'menu_icon' => 'dashicons-format-aside',
            'menu_position' => 6,
            'labels' => array(
                'all_items' => __('All '.ucfirst(Helpers::CPT_NAME_PLU), Helpers::LOCALE),
                'add_new' => __('Add New', Helpers::LOCALE),
                'add_new_item' => __('Add New '.ucfirst(Helpers::CPT_NAME_SING).' Post', Helpers::LOCALE),
                'edit_item' => __('Edit '.ucfirst(Helpers::CPT_NAME_SING).' Post', Helpers::LOCALE),
                'singular_name' => __(Helpers::CPT_NAME_SING, Helpers::LOCALE)
            ),
            'show_in_rest' => TRUE,
            'has_archive' => TRUE,
            'rewrite' => array('slug' => 'projects'),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'author'),
        );
        register_post_type( Helpers::CPT_NAME_SING, $args );
        register_taxonomy_for_object_type('category', Helpers::CPT_NAME_SING);
        register_taxonomy_for_object_type('post_tag', Helpers::CPT_NAME_SING);
    }

    private function set_user_metaboxes($user_id = null) {
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");
        $positions = array(
            "side" => array('submitdiv','authordiv','slugdiv','categorydiv','tagsdiv-post_tag','postimagediv'),
            "normal" => array('postexcerpt'),
            "advanced" => array()
        );

        if(!empty($fields["extra"])) {
            foreach ($fields["extra"] as $fieldkey => $fieldvalue) {
                if ($fieldvalue['metabox']){
                    array_push($positions[$fieldvalue['metabox']['context']], $fieldvalue['metabox']['id']);
                }
            }
        }

        if (is_plugin_active("wordpress-seo/wp-seo.php")){
            array_push($positions["normal"], "wpseo_meta");
        }

        $meta_value_order = array(
            'side' => implode(",", $positions["side"]),
            'normal' => implode(",", $positions["normal"]),
            'advanced' => implode(",", $positions["advanced"]),
        );

        $meta_value_hide = array();

        $meta_key['order'] = 'meta-box-order_project';
        $meta_key['hidden'] = 'metaboxhidden_project';

        if (!$user_id){
            $user_id = get_current_user_id();
        }
        $order = get_user_meta($user_id, $meta_key['order'], true);
        if ($order !== $meta_value_order) {
            update_user_meta( $user_id, $meta_key['order'], $meta_value_order);
        }
        $hidden = get_user_meta($user_id, $meta_key['hidden'], true);
        if ($hidden !== $meta_value_hide) {
            update_user_meta( $user_id, $meta_key['hidden'], $meta_value_hide);
        }
    }
}