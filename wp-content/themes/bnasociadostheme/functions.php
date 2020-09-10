<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu'] = new Timber\Menu();
		$context['site'] = $this;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
                $this->addFunctions($twig);
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

        public function addFunctions($twig) {
            $functions = $this->getFunctions();

            foreach ($functions as $function) {
                $twig->addFunction($function);
            }
        }

        public function getFunctions() {

            $functions = array(
                'getPathCode' => new \Twig_SimpleFunction('getPathCode', array($this,'getPathCode')),
                'renderFeaturedGallery' => new \Twig_SimpleFunction('renderFeaturedGallery', array($this,'renderFeaturedGallery')),
                'do_shortcode' => new \Twig_SimpleFunction('do_shortcode', array($this,'do_shortcode')),
            );

            return $functions;
        }

        public function getPathCode() {
            return get_template_directory_uri();
        }

        public function renderFeaturedGallery($post) {
            if (isset($post->featuredgallery) && !empty($post->featuredgallery)) {
                $featuredGallery = $post->featuredgallery;
                return $this->do_shortcode($featuredGallery);
            }
        }

        public function do_shortcode($content, $ignore_html = false) {
            return do_shortcode($content, $ignore_html);
        }
}

new StarterSite();

const MAX_PROJECTS_ARCHIVE = 48;

//add_filter( 'template_include', 'template_include', 99 );
//add_action( 'parse_query', 'parse_query', 99, 2 );
add_filter('term_link', 'tag_custom_term_link', 10, 3);
add_filter('request', 'tag_custom_term_request', 1, 1 );
add_filter('timber/context', 'add_to_context');
add_action('admin_menu', 'adminMenu');
add_action('admin_bar_menu', 'remove_from_admin_bar',999);

function tag_custom_term_request($query){

    $name = "";
    if (isset($query['category_name'])) {
        $name = $query['category_name'];
    } elseif (isset($query['year'])) {
        $name = $query['year'];
    } elseif (isset($query['name'])) {
        $name = $query['name'];
    }

    $term = get_term_by('slug', $name, 'project_status'); // get the current term to make sure it exists

    if (isset($name) && $term && !is_wp_error($term)){
        unset($query['category_name']);
        $query['project_status'] = $name;
    }

    return $query;
}

function tag_custom_term_link($term_link, $term, $taxonomy){

    if ($taxonomy === 'category'){
        if ( strpos($term_link, 'category') === FALSE ){
            return $term_link;
        }
        $term_link = str_replace('/' . 'category', '', $term_link);
    } else if ($taxonomy === 'post_tag') {
        if ( strpos($term_link, 'tag') === FALSE ){
            return $term_link;
        }
        $term_link = str_replace('/' . 'tag', '', $term_link);
    }

    return $term_link;
}

function add_to_context( $context ) {
    switch (pll_current_language()){
        case 'es':
            $context['menu'] = new \Timber\Menu( 'principal' );
        break;
        case 'en':
            $context['menu'] = new \Timber\Menu( 'main' );
        break;
        default:
            $context['menu'] = new \Timber\Menu( 'principal' );
        break;
    }
    return $context;
}

function template_include( $template ) {

    list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
    $req_uri = trim($req_uri, '/');

    if(preg_match("/year\/([0-9]+)\/$/",$req_uri,$matches) || preg_match("/year\/([0-9]+)\/page\/([0-9]+)$/",$req_uri,$matches)) {
        $new_template = locate_template(array('videos.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }

    return $template;
}

function parse_query( $query ) {

    list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
    $req_uri = trim($req_uri, '/');

    if(preg_match("/videos.html\/?$/",$req_uri,$matches) || preg_match("/videos\/page\/([0-9]+)$/",$req_uri,$matches)) {
        $query->is_home = false;
        $query->is_archive = true;
    }
}

function adminMenu() {
    $user = wp_get_current_user();
    $enable_roles = array('administrator');
    if(!array_intersect($enable_roles, $user->roles ) ) {
        /*remove_post_type_support("post", 'custom-fields');
        remove_post_type_support("post", 'post-formats');
        remove_post_type_support("post", 'revisions');
        remove_post_type_support("post", 'comments');
        remove_post_type_support("post", 'trackbacks');*/
        remove_menu_page( 'edit-comments.php' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'edit.php?post_type=page' );
        remove_menu_page( 'edit.php' );
    }
}

function remove_from_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('new-page');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('about');
    $wp_admin_bar->remove_node('wporg');
    $wp_admin_bar->remove_node('documentation');
    $wp_admin_bar->remove_node('support-forums');
    $wp_admin_bar->remove_node('feedback');
}
