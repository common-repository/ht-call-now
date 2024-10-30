<?php
/**
 * Plugin Name: HT CALL NOW
 * Plugin URI:  https://htweb.vn/plugin/ht-call-now.zip
 * Description: The plugin add button call me now for website
 * Author:      HTWeb
 * Version:     1.0.1
 * Author URI: https://htweb.vn/
 */

define( 'HT_CALL_NOW_VERSION', '1.0' );
define( 'HT_CALL_NOW_PATH', plugin_dir_path( __FILE__ ) );
define( 'HT_CALL_NOW_URL', plugin_dir_url( __FILE__ ) );

class HTCN {
	private static $instance;

	public static function init() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
	}


	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_hooks' ) );
	}

    /**
	 * Constructor
	 */
	public function init_hooks() {
		// Plugin Details
        $this->plugin               = new stdClass;
        $this->plugin->name         = 'ht-call-now'; // Plugin Folder
        $this->plugin->displayName  = 'Ht call now'; // Plugin Name
        $this->plugin->version      = '1.0.0';
        $this->plugin->folder       = plugin_dir_path( __FILE__ );
        $this->plugin->url          = plugin_dir_url( __FILE__ );

		
		add_action( 'admin_menu', array( $this, 'set_menu_panel' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'ht_scripts' ) );
		add_action( 'wp_footer', array( $this, 'view_ht_call_now' ) );

		if ( isset( $_REQUEST['page'] ) && 'ht-call-now' == $_REQUEST['page'] ) {
			$this->save();
		}
	}

	/**
    * Register the plugin settings panel
    */
    function set_menu_panel() {
    	add_submenu_page( 'options-general.php', $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array( $this, 'templatebackend' ) );
	}

	public function templatebackend() {
		include HT_CALL_NOW_PATH . 'includes/templatebackend.php';
	}

	public function data() {
		$defaults = array(
			'phone'       => '',
			'bg_color'	  => '',
			'color'       => '', 
			'style'       => '',

		);

		$data = $this->ht_options( 'ht_options', true );

		if ( ! is_array( $data ) || empty( $data ) ) {
			return $defaults;
		}

		if ( is_array( $data ) && ! empty( $data ) ) {
			return wp_parse_args( $data, $defaults );
		}
	}

	public function ht_options( $key ) {
		$value = get_option( $key );
		return $value;
	}

	public function form_action( $type = '' ) {
		return admin_url( '/admin.php?page=ht-call-now' . $type );
	}

	private function save() {
		if ( ! isset( $_POST['ht-settings-nonce'] ) || ! wp_verify_nonce( $_POST['ht-settings-nonce'], 'ht-settings' ) ) {
			return;
		}

		$data = $this->data();

		$data['phone']       = isset( $_POST['ht_options']['phone'] ) ? sanitize_text_field( $_POST['ht_options']['phone'] ) : '';
		$data['bg_color']       = isset( $_POST['ht_options']['bg_color'] ) ? sanitize_text_field( $_POST['ht_options']['bg_color'] ) : '';
		$data['color']       = isset( $_POST['ht_options']['color'] ) ? sanitize_text_field( $_POST['ht_options']['color'] ) : '';
		$data['style']       = isset( $_POST['ht_options']['style'] ) ? sanitize_text_field( $_POST['ht_options']['style'] ) : '';
		
		update_site_option( 'ht_options', $data );
	}

	public function alert() {
		if ( ! empty( $_POST ) ) {
			echo '<div class="updated"><p>' . esc_html__( 'Settings updated!', 'ht-call-now' ) . '</p></div>';
		}
	}

	public function ht_scripts() {
		// Theme stylesheet.
		wp_enqueue_style( 'ht-call-now', $this->plugin->url.'assets/css/style.css' );
		wp_enqueue_style( 'awesome', $this->plugin->url.'assets/css/font-awesome.min.css' );
	}

	function view_ht_call_now() {
		$data = $this->data();
		$hotline = $data['phone'];
		$bg_color =$data['bg_color'];
		$color =$data['color'];
		$style =$data['style'];
		?>
		<a class="ht-call-now" href="tel:<?php echo $hotline; ?>">			
			<span class="ringing_phone">
				<span class="ringing_phone_before" style="border-color:<?php echo $bg_color;?>;"></span>
			    <i class="fa fa-phone" style="background-color:<?php echo $bg_color;?>;color: <?php echo $color; ?>"></i>
			    <span class="ringing_phone_after" style="background-color:<?php echo $bg_color;?>;?>"></span>
		  	</span>
		</a>
		<?php
	} 
}

HTCN::init();




