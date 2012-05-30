<?php 
/*
Plugin Name:  DashPress
Plugin URI: http://wordpress.org/extend/plugins/dashpress/
Description: The ultimate Dashboard management plugin
Author: Andre Renaut
Version: 3.3
Author URI: http://www.nogent94.com
*/

define ('DBP_FOLDER', 		basename(dirname(__FILE__)));
define ('DBP_PLUGIN_DIR', 	PLUGINDIR . '/' . DBP_FOLDER . '/' );
define ('DBP_PLUGIN_URL', 	get_option('siteurl') . '/' . PLUGINDIR . '/' . DBP_FOLDER . '/' );
define ('DBP_PLUGIN_PATH', 	dirname(__FILE__) . '/');

class DashPress {
	
	const screen 	 = 'dashpress';

	const option_name  = 'plugin_dashpress_core_options';
	const option_boxes = 'plugin_dashpress_core_boxes';
	const option_wdgt  = 'plugin_dashpress_core_widgets';

	const txt_domain 	 = 'DashPress';

	const maxwidgets	 = 5;

	const paypal = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHLwYJKoZIhvcNAQcEoIIHIDCCBxwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYAqRkX4C4DkKhlt+D78RcsoJG+1alo35sSP+J6cnUmmZkEMhj4uftIHZhCJo4z4HxtYlFEv2F+W/ZzLc7d3qovv2nw3duux6p/svz9h5TSsYI0wSxHvqHYbFNZAmjMzGX1P+n9URZ9TAzJl1Y8Bj9xyb9HRBzzTXFDSnAZgBhDOpzELMAkGBSsOAwIaBQAwgawGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIFYSBfJC3J/6AgYjxUrPVZ1yWswVTF0hj60WU5A2SF31fFYFO8lowfiSANzy/T6A2rjygAOsdL94twpfdugH5SsECDbjCGrahGj1oJZJpeC7DRQterckRAmXuQr4CP3wpEpR2DgtjUvaP1f0f4s3eKlWzfOgd2HrwynkFZkhbJmwUKMQolUyi/HNpEchW8yK1E/TSoIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTAxMjIxMTAxNTE2WjAjBgkqhkiG9w0BCQQxFgQUTVQwgTSOjP/Cu2jWFbhn5g0MeTEwDQYJKoZIhvcNAQEBBQAEgYAdm3p2kdTuL0891MZg6kbWjPm3XnmWe4udR3VHHctWq+lG3mdD2QpOMZB9uuHf+Gmy/FAEy4gTscMD36AzCf0orJonNrlRbw+g3TQr+A74+eXZySqg2JJ04NiBLOBcrNVLZMIBqmJBdStSfCD6X/mvvZVNKHl6U5Y5xyvzJgP8tQ==-----END PKCS7-----
"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>';

	function __construct() 
	{
		if (is_admin())
			add_action('wp_dashboard_setup', 	array(__CLASS__, 'wp_dashboard_setup'), 1000 );

		add_action(	'wp_ajax_dbp_ajax',		array(__CLASS__, 'wp_ajax_dbp_ajax'));
		add_action(	'wp_ajax_dbp_count',		array(__CLASS__, 'wp_ajax_dbp_count'));
		add_action(	'wp_ajax_dbp_metabox',	array(__CLASS__, 'wp_ajax_dbp_metabox'));
		add_action(	'wp_ajax_dbp_globset',	array(__CLASS__, 'wp_ajax_dbp_globset'));
	}

	public static function wp_dashboard_setup()
	{
	// for widget
		if (!current_user_can( 'edit_dashboard' ))
		{
			$count = get_option(self::option_wdgt);
			$count = (is_array($count)) ? count($count) : 0;
			if (!$count) return;
		}
 		else
		{
			$count = get_user_option(self::option_name);
			if (!$count) self::update_user_option(self::option_name, $count = 1);
		}

	// for gettext
		load_plugin_textdomain(self::txt_domain, false, DBP_PLUGIN_DIR . 'dbp-content/languages');

	// for css
		wp_register_style ( self::screen, 		'/' . DBP_PLUGIN_DIR . 'dbp-admin/css/dbp.css' );
		wp_enqueue_style(self::screen);

		$pathcss		= DBP_PLUGIN_PATH . 'dbp-admin/css/colors_' . get_user_option('admin_color') . '.css';
		$css_url		= '/' . DBP_PLUGIN_DIR . 'dbp-admin/css/colors_' . get_user_option('admin_color') . '.css';
		$css_url_default 	= '/' . DBP_PLUGIN_DIR . 'dbp-admin/css/colors_fresh.css';
		$css_url		= (is_file($pathcss)) ? $css_url : $css_url_default;
		wp_register_style ( self::screen . '_colors', 	$css_url );
		wp_enqueue_style  ( self::screen . '_colors' );

	// for javascript
		add_action('admin_footer', array(__CLASS__, 'admin_footer'));

		wp_register_script ( self::screen,		'/' . DBP_PLUGIN_DIR . 'dbp-admin/js/dbp.js', false, false, 1);
		wp_localize_script ( self::screen, 		'dbpL10n', array( 
			'url' 		=> admin_url('admin-ajax.php'),
			'can_edit'		=> current_user_can( 'edit_dashboard' ) ? 1 : 0,
			'set'			=> esc_js(__('Set default', self::txt_domain)),
			'erase'		=> esc_js(__('Erase default', self::txt_domain)),
		));
		wp_enqueue_script(self::screen);

	// adding widgets
		require_once(DBP_PLUGIN_PATH . 'dbp-admin/class/DBP_Widget.class.php');
		for ( $i = 1; $i <= $count ; $i++ ) new DBP_Widget($i);

	// for filtering widgets
		global $wp_meta_boxes, $dbp_boxes;

		$page = 'dashboard';
		$visible = (current_user_can( 'edit_dashboard' )) ? get_user_option(self::option_boxes) : get_option(self::option_boxes);
                if (!is_array($visible)) $visible = array();
		foreach ( array_keys($wp_meta_boxes[$page]) as $context )
		{ 
			foreach ( array_keys($wp_meta_boxes[$page][$context]) as $priority ) 
			{
				foreach ( $wp_meta_boxes[$page][$context][$priority] as $key => $box ) 
				{
					if ($visible && !in_array($key, $visible)) unset($wp_meta_boxes[$page][$context][$priority][$key]);
					$dbp_boxes[] = array('id' => $box['id'], 'title' => (strpos($box['title'], ' <span') ? substr($box['title'],0,strpos($box['title'], ' <span')) : $box['title']), 'checked' => (in_array($box['id'], $visible)) ? 1 : 0);
					$init[] = $box['id'];
				}
			}
		}

		if (!$visible) self::update_user_option(self::option_boxes, $init);
	}

	public static function admin_footer() 
	{
		if (!current_user_can( 'edit_dashboard' )) return;
		global $dbp_boxes;

		$value = (get_option(self::option_wdgt)) ? __('Erase default', self::txt_domain) : __('Set default', self::txt_domain);
?>
<div id='dashboard-options-wrap' class='hidden'>
	<div style='float:right;padding:4px 0;'>
		<?php echo self::paypal; ?>
	</div>
	<form id='adv-dashboard-settings' method='post' action=''>
		<h5><?php _e('Show on Dashboard', self::txt_domain); ?></h5>
		<div class='metabox-prefs'>
<?php
		foreach($dbp_boxes as $dbp_box)
		{
			$checked = ($dbp_box['checked'] == '1') ?  " checked='checked'" : '';
			$id      = $dbp_box['id'];
			$title   = $dbp_box['title'];
?>
			<label for='<?php echo $id; ?>-dbp'><input type='checkbox'<?php echo $checked; ?> value='<?php echo $id; ?>' id='<?php echo $id; ?>-dbp' name='<?php echo $id; ?>-dbp' class='hide-dashbox-tog' /><?php echo $title; ?></label>
<?php
		}
?>
			<br class='clear' />
		</div>
			<h5><?php _e('DashPress Option', self::txt_domain); ?></h5>
		<div class='widgets-prefs'>
			<input id='dashpress-global-settings' type='button' value="<?php echo esc_attr($value); ?>" style='float:right;' />
			<?php _e('Number of DashPress widgets:', self::txt_domain); ?>
<?php
		$count = get_user_option(self::option_name);
		for ($i = 1; $i <= self::maxwidgets; $i++) 
		{
			$checked = ($i == $count) ?  " checked='checked'" : '';
?>
			<label><input class='dbp_option'<?php echo $checked; ?> name='dbp_option' value='<?php echo $i; ?>' type='radio' /><?php echo $i; ?></label>
<?php
		}
?>
		</div>
	</form>
</div>
<div id='dashboard-options-link-wrap' class='hide-if-no-js screen-meta-toggle'>
	<a id='dashboard-options-link' class='show-settings' href='#dashboard-options-wrap'>
		<?php _e('Dashboard Options', self::txt_domain); ?>
	</a>
</div>
<?php
	}

	public static function update_user_option( $option_name, $newvalue) 
	{
		global $user_ID;
		return update_user_option($user_ID, $option_name, $newvalue);
	}

	public static function wp_ajax_dbp_ajax()
	{
		require_once(DBP_PLUGIN_PATH . 'dbp-admin/class/DBP_Widget.class.php');
		$dbps = new DBP_Widget($_POST['i']);
		do_action('DBP_get_content_' . $_POST['i']);
		die();
	}

	public static function wp_ajax_dbp_count()
	{
		die(self::update_user_option(self::option_name, $_POST['count']));
	}

	public static function wp_ajax_dbp_metabox()
	{
		$boxes = get_user_option(self::option_boxes);
		$boxes = array_flip($boxes);
		if ($_POST['checked']) 	$boxes[$_POST['box']] = true;
		else 				unset($boxes[$_POST['box']]);
		die(self::update_user_option(self::option_boxes, array_keys($boxes)));
	}

	public static function wp_ajax_dbp_globset()
	{
		if (get_option(self::option_boxes) !== false)
		{
			delete_option(self::option_boxes);
			delete_option(self::option_wdgt);
			die('0');
		}
		update_option(self::option_boxes, get_user_option( self::option_boxes ));
		update_option(self::option_wdgt , get_user_option( self::option_wdgt  ));
		die('1');
	}
}
$DashPress = new DashPress();
?>