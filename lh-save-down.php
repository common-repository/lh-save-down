<?php
/**
 * Plugin Name: LH Save down
 * Plugin URI: https://lhero.org/portfolio/lh-save-down/
 * Description: Saves post as text or html attachment, enabling you to download content
 * Author: Peter Shaw
 * Version: 2.20
 * Text Domain: lh_save_down
 * Author URI: https://shawfactor.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('lhsdItems')) {

  class lhsdItems {
    public $db_id = 0;
    public $object = 'lh_save_down_menu_item';
    public $object_id;
    public $menu_item_parent = 0;
    public $type = 'custom';
    public $title;
    public $url;
    public $target = '';
    public $description = '';
    public $attr_title = '';
    public $classes = array();
    public $xfn = '';
  }
  
}


if (!class_exists('LH_save_down_plugin')) {

class LH_save_down_plugin {
    
    private static $instance;
    
static function return_plugin_namespace(){

    return 'lh_save_down';

    }
    
static function curpageurl() {
	$pageURL = 'http';

	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
		$pageURL .= "s";
}

	$pageURL .= "://";

	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

}

	return $pageURL;
}

static function toepub($html, $options) {
		
// ePub uses XHTML 1.1, preferably strict.
		require_once "lib/PHPePub/EPub.php";
		$fileepub = $options["filename"] . '.epub';
		$cssData = get_option('bg_forreaders_css');

// The mandatory fields		
		$epub = new EPub();
		$epub->setTitle($options["title"]); 
		$epub->setLanguage($options["lang"]);			
		$epub->setIdentifier($options["guid"], EPub::IDENTIFIER_URI); 
// The additional optional fields
		$epub->setAuthor($options["author"], ""); // "Firstname, Lastname"
		$epub->setPublisher(get_bloginfo( 'name' ), get_bloginfo( 'url' ));
		$epub->setSourceURL($options["url"]);
		
		if ($options["thumb"]) $epub->setCoverImage($options["thumb"]);
		
		$epub->addCSSFile("styles.css", "css1", $cssData);			
		$html =
		"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
		. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
		. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
		. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
		. "<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">"
		. "\n"
		. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
		. "<title>" . $options["title"] . "</title>\n"
		. "</head>\n"
		. "<body>\n"
		. $html
		."\n</body>\n</html>\n";
		
		$epub->addChapter("Book", "Book.html", $html, false, EPub::EXTERNAL_REF_ADD, '');
		$epub->finalize();
		$epub->sendBook("$fileepub");
		unset($epub);
		$epub=NULL;
		return;
	}  
    
    
    



public function do_html() {
    
if (!empty($_GET[ self::return_plugin_namespace().'-nonce' ]) && wp_verify_nonce($_GET[ self::return_plugin_namespace().'-nonce' ], self::return_plugin_namespace().'-nonce' )){
    
    global $post;
    
ob_start();

if (file_exists(get_stylesheet_directory().'/lh_save_down-html-post_template.php')){

$dir = get_stylesheet_directory().'/lh_save_down-html-post_template.php';

} else {

$dir = plugin_dir_path( __FILE__ ).'/templates/'.'lh_save_down-html-post_template.php';

}  





require_once($dir);


$output = ob_get_contents();

ob_end_clean();



    $title = strtolower(sanitize_file_name(get_the_title()));

    $length = strlen($output);

    header ("Content-Type: text/html; charset=utf-8");
    header ("Content-Length: {$length}"); 
    header ("Content-Disposition: attachment;filename={$title}.html");

    echo $output; 
    
}

exit;
 
    
}


public function do_text() {
    
if (!empty($_GET[ self::return_plugin_namespace().'-nonce' ]) && wp_verify_nonce($_GET[ self::return_plugin_namespace().'-nonce' ], self::return_plugin_namespace().'-nonce' )){
    
ob_start();

if (file_exists(get_stylesheet_directory().'/lh_save_down-text-post_template.php')){

$dir = get_stylesheet_directory().'/lh_save_down-text-post_template.php';

} else {

$dir = plugin_dir_path( __FILE__ ).'/templates/'.'lh_save_down-text-post_template.php';

}  





require_once($dir);

$output = ob_get_contents();

ob_end_clean();

$breaks = array("<br />","<br>","<br/>");  
$output = str_ireplace($breaks, "\r\n", $output);  

$output = wp_strip_all_tags($output);



    $title = strtolower(sanitize_file_name(get_the_title()));

    $length = strlen($output);

    header ("Content-Type: text/plain; charset=utf-8");
    header ("Content-Length: {$length}"); 
    header ("Content-Disposition: attachment;filename={$title}.txt");

    echo $output;
    
}

exit;
 
    
}

public function do_pdf() {
    
if (!empty($_GET[ self::return_plugin_namespace().'-nonce' ]) && wp_verify_nonce($_GET[ self::return_plugin_namespace().'-nonce' ], self::return_plugin_namespace().'-nonce' )){
    
   ob_start();

if (file_exists(get_stylesheet_directory().'/lh_save_down-pdf-post_template.php')){

$dir = get_stylesheet_directory().'/lh_save_down-pdf-post_template.php';

} else {

$dir = plugin_dir_path( __FILE__ ).'/templates/'.'lh_save_down-pdf-post_template.php';

}  



require_once($dir);


$output = ob_get_contents();

ob_end_clean();


if (!class_exists('mPDF')) {


include_once( 'lib/mpdf_new/autoload.php' );



}

$filename = strtolower(sanitize_file_name(get_the_title())).'-'.date( 'Y-m-d-U' );

$mpdf = new \Mpdf\Mpdf();

$mpdf->WriteHTML($output);

$mpdf->Output($filename.'.pdf', 'I');

}

	exit; 
    
    
    
    
}

public function do_epub() {
    
if (!empty($_GET[ self::return_plugin_namespace().'-nonce' ]) && wp_verify_nonce($_GET[ self::return_plugin_namespace().'-nonce' ], self::return_plugin_namespace().'-nonce' )){
    
    global $post;
    
 ob_start();

if (file_exists(get_stylesheet_directory().'/lh_save_down-html-post_template.php')){

$dir = get_stylesheet_directory().'/lh_save_down-html-post_template.php';

} else {

$dir = plugin_dir_path( __FILE__ ).'/templates/'.'lh_save_down-html-post_template.php';

}  





require_once($dir);


$output = ob_get_contents();  

ob_end_clean();

	$author_id = get_user_by( 'ID', $post->post_author ); 	// Get user object
			$author = $author_id->display_name;						// Get user display name
$lang = get_bloginfo('language');


		$options = array(
			"title"=> strip_tags($post->post_title),
			"author"=> $author,
			"guid"=>$post->guid,
			"url"=>$post->guid,
			"filename"=> strtolower(sanitize_file_name(get_the_title())),
			"lang"=>$lang
		);

self::toepub($output, $options);

} else {
    
    exit;
    
}
    
}

public function add_feeds() {

add_feed('lh-save_down.html', array($this, 'do_html'));
add_feed('lh-save_down.text', array($this, 'do_text'));
add_feed('lh-save_down.pdf', array($this, 'do_pdf'));
add_feed('lh-save_down.epub', array($this, 'do_epub'));



}

    
/* The metabox code : Awesome code stolen from screenfeed.fr (GregLone) Thank you mate. */
public function nav_menu_metabox($object) {
  global $nav_menu_selected_id;

  $elems = array(
    '#lhsd_html#' => __('Plain HTML', self::return_plugin_namespace()),
    '#lhsd_text#' => __('Plain Text', self::return_plugin_namespace()),
    '#lhsd_pdf#' => __('PDF', self::return_plugin_namespace()),
    '#lhsd_epub#' => __('Epub', self::return_plugin_namespace())
  );
  


  $elems_obj = array();

  foreach($elems as $value => $title) {
    $elems_obj[$title]              = new lhsdItems();
    $elems_obj[$title]->object_id		= esc_attr($value);
    $elems_obj[$title]->title			  = esc_attr($title);
    $elems_obj[$title]->url			    = esc_attr($value);
  }

  $walker = new Walker_Nav_Menu_Checklist(array());

  ?>
  <div id="<?php self::return_plugin_namespace(); ?>-links" class="<?php self::return_plugin_namespace(); ?>linksdiv">
    <div id="tabs-panel-<?php self::return_plugin_namespace(); ?>-links-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
      <ul id="<?php self::return_plugin_namespace(); ?>-linkschecklist" class="list:<?php self::return_plugin_namespace(); ?>-links categorychecklist form-no-clear">
        <?php echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $elems_obj), 0, (object) array('walker' => $walker)); ?>
      </ul>
    </div>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit"<?php disabled($nav_menu_selected_id, 0); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu', 'lolmi'); ?>" name="add-<?php self::return_plugin_namespace(); ?>-links-menu-item" id="submit-<?php self::return_plugin_namespace(); ?>-links" />
        <span class="spinner"></span>
      </span>
    </p>
  </div>
  <?php
}


/* Add a metabox in admin menu page */
public function add_nav_menu_metabox() {
  add_meta_box(self::return_plugin_namespace().'-loginout_metabox', __('Save Down Menu Items', self::return_plugin_namespace()), array($this, 'nav_menu_metabox'), 'nav-menus', 'side', 'default');
}

    /* Modify the "type_label" */
    public function nav_menu_type_label($menu_item) {
        
        $elems = array('#lhsd_html#', '#lhsd_text#','#lhsd_pdf#','#lhsd_epub#');
        
        if(isset($menu_item->object, $menu_item->url) && 'custom' == $menu_item->object && in_array($menu_item->url, $elems)) {
        
            $menu_item->type_label = __('Save Down Link', self::return_plugin_namespace());
        }
    
      return $menu_item;
      
    }

    /* The main code, this replace the #keyword# by the correct links with nonce ect */
    public function setup_nav_menu_item($item) {
    	global $pagenow;
    
    	if ($pagenow != 'nav-menus.php' && !defined('DOING_AJAX') && isset($item->url) && (strstr($item->url, '#lhsd') != '')) {
    	    
    	    if (!is_singular() or (function_exists ('bp_current_component') && bp_current_component())){
    	        
    	        $item->_invalid = true;
    	   
    	        
    	    } else {
    	    
    	        if ( wp_get_canonical_url() && is_singular()) {
    	        
    	            $link_url = wp_get_canonical_url();
    		        
    		      
    		    } else   {
    		        
    		        $link_url = self::curpageurl();

    		    }
    	    
    
    
    		    switch($item->url) {

    		        case '#lhsd_html#':
                    $item->url = add_query_arg( self::return_plugin_namespace().'-nonce', wp_create_nonce( self::return_plugin_namespace().'-nonce' ), $link_url.'feed/lh-save_down.html');
                    break;
                    
    			    case '#lhsd_text#':
                    $item->url = add_query_arg( self::return_plugin_namespace().'-nonce', wp_create_nonce( self::return_plugin_namespace().'-nonce' ), $link_url.'feed/lh-save_down.txt');
                    break;
            	
            	    case '#lhsd_pdf#':
                    $item->url = add_query_arg( self::return_plugin_namespace().'-nonce', wp_create_nonce( self::return_plugin_namespace().'-nonce' ), $link_url.'feed/lh-save_down.pdf');
                    break;
    			
    			    case '#lhsd_epub#':
                    $item->url = add_query_arg( self::return_plugin_namespace().'-nonce', wp_create_nonce( self::return_plugin_namespace().'-nonce' ), $link_url.'feed/lh-save_down.epub');;
    
    		    }
    		
    	    }
    	}
    
    	return $item;
    	
    }

    public function add_front_end_hooks(){
        
        add_filter('wp_setup_nav_menu_item', array($this, 'setup_nav_menu_item'), 10, 1);
        
    }
    
    public function add_back_end_hooks(){
        
        add_action('admin_head-nav-menus.php', array($this,'add_nav_menu_metabox'));

        add_filter('wp_setup_nav_menu_item', array($this,'nav_menu_type_label'), 10, 1);
        
    }



    public function plugin_init(){
        
        //load translations
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 
        
        // add the feeds
        add_action('init', array($this, 'add_feeds'));
        
        // add front end hooks
        add_action('template_redirect', array($this, 'add_front_end_hooks'));
        
        // add back end hooks
        add_action('admin_init', array($this, 'add_back_end_hooks'));
    
    }


    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
     
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }



    public function __construct() {
    
            //run our hooks on plugins loaded to as we may need checks       
        add_action( 'plugins_loaded', array($this,'plugin_init'));
    
    
    }



}

$lh_save_down_instance = LH_save_down_plugin::get_instance();

}

?>