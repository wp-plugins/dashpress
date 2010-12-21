<?php
class DBP_Widget
{
	function __construct($i)
	{
		$this->i  = $i;
		$this->id = (1 == $this->i) ? 'dashpress' : 'dashpress_' . $this->i;

		$options = (current_user_can( 'edit_dashboard' )) ? get_user_option( DashPress::option_wdgt ) : get_option( DashPress::option_wdgt );
		$this->options = $this->clean_options(isset($options[$this->id]) ? $options[$this->id] : array());

		if ( function_exists('wp_add_dashboard_widget') )
			wp_add_dashboard_widget(	$this->id,
								$this->options['wtitle'],
								array(&$this, 'widget'),
								array(&$this, 'control') 
			);

		add_action('DBP_get_content_' . $this->i, array(&$this, 'get_content'), 8);
	}

	function widget() 
	{
		if (	!isset($this->options['feeds']) ) 
		{
			echo '<p class="widget-loading hide-if-no-js">' . __('First time ! welcome ! you have to edit the control panel&#8230;',DashPress::txt_domain ) . "</p>\n";
			return;
		}

		if (	0 < count($this->options['feeds']) ) 
		{
			echo '<p class="widget-loading hide-if-no-js dbp_widget" id="dbp_widget_' . $this->i . '">' . __( 'Loading&#8230;' ) . '</p><p class="describe hide-if-js">' . __('This widget requires JavaScript.') . '</p>';
			return;
		}

		echo '<p class="widget-loading hide-if-no-js">' . __('No feed requested, you should edit the control panel&#8230;', DashPress::txt_domain ) . "</p>\n";
	}

	function control()
	{
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST[$this->id]) )
		{
			$this->options = $this->clean_options($_POST[$this->id]);

			$options = (current_user_can( 'edit_dashboard' )) ? get_user_option( DashPress::option_wdgt ) : get_option( DashPress::option_wdgt );
			$options[$this->id] = $this->options;
			DashPress::update_user_option( DashPress::option_wdgt, $options );
		}
		$cachings = array(
					300 		=> __('5 min',    DashPress::txt_domain ),
					900 		=> __('15 min',   DashPress::txt_domain ),
					1800 		=> __('30 min',   DashPress::txt_domain ),
					3600		=> __('1 hour',   DashPress::txt_domain ),
					7200 		=> __('2 hours',  DashPress::txt_domain ),
					14400		=> __('4 hours',  DashPress::txt_domain ),
					28800		=> __('8 hours',  DashPress::txt_domain ),
					43200		=> __('12 hours', DashPress::txt_domain ),
					96400		=> __('1 day',    DashPress::txt_domain ),
		);
?>
	<p style='margin-bottom:0;'>
		<?php _e('Widget', DashPress::txt_domain ); ?>
	</p>
	<p style='margin:3px;padding:3px;border:1px solid #666;background-color:#eeeeee;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;'>
		<input class="widefat" type="text" value="<?php echo $this->options['wtitle'] ; ?>" name="<?php echo $this->id; ?>[wtitle]" autocomplete="off" />
<br />
		<?php _e('Height : ', DashPress::txt_domain ); ?>&nbsp;<select name="<?php echo $this->id; ?>[height]"><?php for ( $i = 5; $i <= 85; $i = $i+5 ) echo "<option value='$i'" . ( $this->options['height'] == $i ? " selected='selected'" : '' ) . ">$i</option>"; ?></select>(em)
	</p>
	<p style='margin-bottom:0;'>
		<?php _e('Feeds', DashPress::txt_domain ); ?>
	</p>
	<p style='margin:3px;padding:3px;border:1px solid #666;background-color:#eeeeee;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;'>
		<?php _e('Posts : ', DashPress::txt_domain ); ?>&nbsp;<select name="<?php echo $this->id; ?>[maxlines]"><?php for ( $i = 3; $i <= 99; $i++ ) echo "<option value='$i'" . ( $this->options['maxlines'] == $i ? " selected='selected'" : '' ) . ">$i</option>"; ?></select>
		&nbsp;&nbsp;
		<?php _e('Image : ', DashPress::txt_domain ); ?>&nbsp;<input type="checkbox" <?php checked(isset($this->options['image'])); ?> name="<?php echo $this->id; ?>[image]" id="<?php echo $this->id; ?>_image" />
		&nbsp;&nbsp;
<br />
		<?php _e('Input : ', DashPress::txt_domain ); ?>&nbsp;<select name="<?php echo $this->id; ?>[maxfeeds]"><?php for ( $i = 3; $i <= 10; $i++ ) echo "<option value='$i'" . ( $this->options['maxfeeds'] == $i ? " selected='selected'" : '' ) . ">$i</option>";?></select>
		&nbsp;&nbsp;
		<?php _e('Caching : ', DashPress::txt_domain ); ?>&nbsp;<select name="<?php echo $this->id; ?>[caching]"><?php foreach($cachings as $key => $value) echo "<option value='$key'" . ( $this->options['height'] == $key ? " selected='selected'" : '' ) . ">$value</option>"; ?></select>
	</p>
	<p>
		<label><?php _e('Fill the RSS or Atom urls here', DashPress::txt_domain ); ?> : </label>
<?php
		$z = 1;
		foreach ( $this->options['feeds'] as $feed )
		{
?>
			<input class="widefat" id="rss-url-<?php echo $z; ?>" name="<?php echo $this->id; ?>[feeds][]" type="text" value="<?php echo clean_url($feed); ?>"  style='margin-bottom:5px;' /><br />
<?php
			$z++;
			if ($z > $this->options['maxfeeds']) break;
		}
		for ( $i = $z; $i <= $this->options['maxfeeds']; $i++ )
		{
?>
			<input class="widefat" id="rss-url-<?php echo $i; ?>" name="<?php echo $this->id; ?>[feeds][]" type="text" value=""  style='margin-bottom:5px;' />
<?php
		}
?>
	</p>
<?php
	}

	function clean_options($options)
	{
		if ( !isset($options['wtitle']) ) 	$options['wtitle'] = __( 'Last News', DashPress::txt_domain ) . ' - ' . $this->i ;;
		if ( !isset($options['image']) )	$options['image'] = false;
		if ( !isset($options['maxfeeds']) )	$options['maxfeeds'] = 3;
		$options['maxfeeds'] = (int) $options['maxfeeds'];
		if ( !isset($options['caching']) )	$options['caching'] = 43200;
		$options['caching'] = (int) $options['caching'];
		if ( !isset($options['maxlines']) )	$options['maxlines'] = 10;
		$options['maxlines'] = (int) $options['maxlines'];
		if ( !isset($options['height']) )	$options['height']   = 20;
		$options['height'] = (int) $options['height'];
		if ( !isset($options['feeds']) )
			$options['feeds'] = array();
		else
		{
			$feeds  = array_filter($options['feeds'],array(&$this, 'not_empty'));
			$feeds  = array_slice ($feeds, 0, $options['maxfeeds']);
			$options['feeds'] = $feeds;
		}
		return $options;
	}

////  ////
////  ////
////  ////


	function get_content()
	{
		require_once  (ABSPATH . WPINC . '/class-feed.php');

		$feed = new SimplePie();
		$feed->set_feed_url($this->options['feeds']);
		$feed->set_cache_class('WP_Feed_Cache');
		$feed->set_file_class('WP_SimplePie_File');
		$feed->set_cache_duration((isset($this->options['caching'])) ? $this->options['caching'] : 43200);
		$feed->init();
		$feed->handle_content_type();
		if ($feed->get_items())
		{
?>
<div class='dbp-content' style='height:<?php echo $this->options['height']; ?>em;'>
	<ul>
<?php
			$z = 1;
			$date_format = get_option('date_format') . ' G:i ';
			foreach ($feed->get_items() as $item)
			{
				$desc = str_replace(array("\n", "\r"), ' ', esc_attr(strip_tags(@html_entity_decode($item->get_description(), ENT_QUOTES, get_option('blog_charset')))));
				$desc = wp_html_excerpt( $desc, 360 ) . ' [&hellip;]';
				$desc = esc_html( $desc );
				$desc = $item->get_feed()->get_title() . " | \n$desc";

				$img    = ($this->options['image']) ? $this->get_image($item) : '';
				$class  = (empty($img)) ? 'noimg' : 'img' ;
?>
		<li class='<?php echo $class; ?>'><?php if ($img) echo "<table><tr><td>\n<div class='img lastnews'>" . $img . '</div>'; ?><span class='lastnews'><a class='lastnews' href='<?php echo $item->get_permalink(); ?>' title='<?php echo $desc; ?>' target='_blank'><?php echo $item->get_title(); ?></a> &#8212; <abbr style='color:#666;' title="<?php echo mysql2date($date_format,$item->get_date('Y-m-d H:i:00')); ?>"><?php echo $item->get_date('Y/m/d'); ?></abbr></span><?php if ($img) echo "</tr></td></table>"; ?></li>
<?php
				$z++;
				if ( $z > $this->options ['maxlines'])  break;
			}
?>
	</ul>
</div>
<?php
		}
		else
		{
			 echo "<p>" . __( "Sorry! no news !", DashPress::txt_domain ) . "</p>\n";
		}
		$feed->__destruct(); 
		unset($feed);
	}

	function get_image($item)
	{
		$img = array();

		$enclosure	=	$item->get_enclosure();
		if (!empty($enclosure))
		{
			$thumbnails = $enclosure->get_thumbnails();
			if ( !empty($thumbnails) ) foreach ( $thumbnails as $thumbnail) 	$img [] =  $thumbnail;
			if ( false !== stripos($enclosure->get_type(),'image') ) 		$img [] =  $enclosure->get_link(); 
			if ( 'image' == $enclosure->get_medium() ) 		 		$img [] =  $enclosure->get_link(); 
			$img [] = $item->get_feed()->get_image_link();
			$img [] = $item->get_feed()->get_image_url();
		}
		if ($img == array())
		{
			$content = $item->get_content();
			$output  = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches, PREG_SET_ORDER);
			$wimg = '';
			if (isset($matches [0] [1])) $wimg = str_replace(' ', '%20',$matches [0] [1]);

			$needles = array ('bookmark.gif');				/* filter any icon of social bookmarkers ! */
			if (!$this->in_string($wimg,$needles)) 			$img [] = $wimg;
		}

		$img = array_filter($img,array(&$this, 'not_empty'));
		$img = array_filter($img,array(&$this, 'is_url'));

		switch (count($img))
		{
			case 0 :
				return '';
			break;
			case 1 :
				return $this->format_img(reset($img));
			break;
			default :
				$default 	= reset($img);
				$img 		= array_filter($img,array(&$this, 'exclude'));
				if ( 0 == count($img) ) return $this->format_img($default);
				else 				return $this->format_img(reset($img));
			break;
		}
	}

/***************************************/
	function in_string($haystack,$needles) 	{ foreach ($needles as $needle) if ((stripos($haystack,$needle) !== false)) return true; return false; }
	function not_empty($var)			{ if (empty($var)) return false; return true; }
	function is_url   ($var)			{ if (stripos($var,'http://') === false) return false; return true; }
	function exclude  ($var)			{ $excludes = array('smilies'); foreach ($excludes as $exclude) if (stripos($var,$exclude) !== false) return false; return true; }
/***************************************/

	function format_img($url)
	{
		$hmin = 10; $hmax = 150;
		$wh = false;

		$wh = @getimagesize($url);
		if ( ($wh [1] < $hmin) || ($wh [1] > $hmax) )
			return  '';
		return  "<img src='$url' class='lastnews' />";
	}
}
?>