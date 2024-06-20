<?php
define('SHFS_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

if ( !class_exists( 'HeaderAndFooterScripts' ) ) {

	class HeaderAndFooterScripts {

		function __construct() {

			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_head', array( &$this, 'wp_head' ), \get_option('shfs_insert_header_priority', 10) );
			add_action( 'wp_footer', array( &$this, 'wp_footer' ), \get_option('shfs_insert_footer_priority', 10) );

		}

		function init() {

			load_plugin_textdomain( 'header-and-footer-scripts', false, dirname( plugin_basename ( __FILE__ ) ).'/lang' );
		}

		function admin_init() {

			// register settings for sitewide script
			register_setting( 'header-and-footer-scripts', 'shfs_insert_header', 'trim' );
			register_setting( 'header-and-footer-scripts', 'shfs_insert_footer', 'trim' );
			register_setting( 'header-and-footer-scripts', 'shfs_insert_header_priority', 'intval' );
			register_setting( 'header-and-footer-scripts', 'shfs_insert_footer_priority', 'intval' );

			// add meta box to all post types
			foreach ( get_post_types( '', 'names' ) as $type ) {
				add_meta_box('shfs_all_post_meta', esc_html__('Insert Script to &lt;head&gt;', 'header-and-footer-scripts'), 'shfs_meta_setup', $type, 'normal', 'high');
			}

			add_action('save_post','shfs_post_meta_save');
		}

		function admin_menu() {
			$page = add_submenu_page( 'options-general.php', esc_html__('Header and Footer Scripts', 'header-and-footer-scripts'), esc_html__('Header and Footer Scripts', 'header-and-footer-scripts'), 'manage_options', __FILE__, array( &$this, 'shfs_options_panel' ) );
			}

		function wp_head() {
			$meta = get_option( 'shfs_insert_header', '' );
			if ( $meta != '' ) {
				echo $meta, "\n";
			}

			$shfs_post_meta = get_post_meta( get_the_ID(), '_inpost_head_script' , TRUE );
			if ( is_singular() && $shfs_post_meta != '' ) {
				echo $shfs_post_meta['synth_header_script'], "\n";
			}

		}

		function wp_footer() {
			if ( !is_admin() && !is_feed() && !is_robots() && !is_trackback() ) {
				$text = get_option( 'shfs_insert_footer', '' );
				$text = convert_smilies( $text );
				$text = do_shortcode( $text );

				if ( $text != '' ) {
					echo $text, "\n";
				}
			}
		}

		function shfs_options_panel() {
				// Load options page
				require_once(SHFS_PLUGIN_DIR . '/inc/options.php');
		}
	}

	function shfs_meta_setup() {
		global $post;

		$meta = get_post_meta($post->ID,'_inpost_head_script',TRUE);
		include_once(SHFS_PLUGIN_DIR . '/inc/meta.php');
		echo '<input type="hidden" name="shfs_post_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}

	function shfs_post_meta_save($post_id) {
		if ( ! isset( $_POST['shfs_post_meta_noncename'] )
			|| !wp_verify_nonce($_POST['shfs_post_meta_noncename'],__FILE__)) return $post_id;

		if ( $_POST['post_type'] == 'page' ) {

			if (!current_user_can('edit_page', $post_id))
				return $post_id;

		} else {

			if (!current_user_can('edit_post', $post_id))
				return $post_id;

		}

		$current_data = get_post_meta($post_id, '_inpost_head_script', TRUE);

		$new_data = $_POST['_inpost_head_script'];

		shfs_post_meta_clean($new_data);

		if ($current_data) {

			if (is_null($new_data)) delete_post_meta($post_id,'_inpost_head_script');

			else update_post_meta($post_id,'_inpost_head_script',$new_data);

		} elseif (!is_null($new_data)) {

			add_post_meta($post_id,'_inpost_head_script',$new_data,TRUE);

		}

		return $post_id;
	}

	function shfs_post_meta_clean(&$arr) {

		if (is_array($arr)) {

			foreach ($arr as $i => $v) {

				if (is_array($arr[$i])) {
					shfs_post_meta_clean($arr[$i]);

					if (!count($arr[$i])) {
						unset($arr[$i]);
					}

				} else {

					if (trim($arr[$i]) == '') {
						unset($arr[$i]);
					}
				}
			}

			if (!count($arr)) {
				$arr = NULL;
			}
		}
	}

	$shfs_header_and_footer_scripts = new HeaderAndFooterScripts();
}
