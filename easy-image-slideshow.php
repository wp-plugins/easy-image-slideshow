<?php

/*
Plugin Name: Easy image slideshow
Plugin URI: http://www.gopiplus.com/work/2012/06/20/easy-image-slideshow-wordpress-plugin/
Description: This is a lightweight JavaScript slideshow with manual navigation option. You can use this slideshow, if you need the manual navigation image gallery.
Author: Gopi.R
Version: 4.0
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2012/06/20/easy-image-slideshow-wordpress-plugin/
Tags: easy, slideshow, images
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("wp_easy_table", $wpdb->prefix . "easyimage_slideshow");


if ( ! defined( 'EASYIMAGE_PLUGIN_BASENAME' ) )
	define( 'EASYIMAGE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'EASYIMAGE_PLUGIN_NAME' ) )
	define( 'EASYIMAGE_PLUGIN_NAME', trim( dirname( EASYIMAGE_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'EASYIMAGE_PLUGIN_DIR' ) )
	define( 'EASYIMAGE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . EASYIMAGE_PLUGIN_NAME );

if ( ! defined( 'EASYIMAGE_PLUGIN_URL' ) )
	define( 'EASYIMAGE_PLUGIN_URL', WP_PLUGIN_URL . '/' . EASYIMAGE_PLUGIN_NAME );

function easyimage_plugin_path( $path = '' ) {
	return path_join( EASYIMAGE_PLUGIN_DIR, trim( $path, '/' ) );
}

function easyimage_plugin_url( $path = '' ) {
	return plugins_url( $path, EASYIMAGE_PLUGIN_BASENAME );
}

function easyimage() 
{
	global $wpdb;
	$url = easyimage_plugin_url('inc');
	$siteurl = get_option('siteurl') . "/";
	
	$easyimage_widget = get_option('easyimage_widget');
	$sSql = "select * from ".wp_easy_table." where 1 = 1";
	if($easyimage_widget <> "")
	{
		$sSql = $sSql . " and easyimage_id=$easyimage_widget";
	}
	
	$sSql = $sSql . " order by rand() limit 0,1;";
	//echo $sSql;
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$data = $data[0];
		$easyimage_location = $data->easyimage_location; 
		$easyimage_width = $data->easyimage_width; 
		$easyimage_height = $data->easyimage_height; 
		$easyimage_random = $data->easyimage_random; 
		
		if(is_dir($easyimage_location))
		{
			$f_dirHandle = opendir($easyimage_location);
			$path = "";
			while ($f_file = readdir($f_dirHandle)) 
			{
				$f_file = strtoupper($f_file);
				if(!is_dir($f_file) && (strpos($f_file, '.JPG')>0 or strpos($f_file, '.GIF')>0 or strpos($f_file, '.PNG')>0)) 
				{
					$path = $path . "'" . $siteurl . $easyimage_location . $f_file . "',";
				}
			}
			closedir($f_dirHandle);
			$path = substr($path,0,(strlen($path)-1));
			?>
			<script type='text/javascript'>
			var SimpleSlides = new Array(<?php echo $path; ?>);
			</script>
			<form name="FormSlideShow">
			<div style="padding-bottom:5px;width:<?php echo $easyimage_width; ?>px;"><img name="EasyImage" id="EasyImage" width="<?php echo $easyimage_width; ?>" height="<?php echo $easyimage_height; ?>"></div>
			<div style="width:<?php echo $easyimage_width; ?>px;height:10px;">
				<span style="float:left;"><img name="Previous" id="Previous" style="cursor:pointer;" src="<?php echo $url; ?>/left.jpg" onClick="SlideShowMoment(-1)" /></span>
				<span style="float:right;"><img name="Next" id="Next" style="cursor:pointer;" src="<?php echo $url; ?>/right.jpg" onClick="SlideShowMoment(1)" /></span>
			</div>
			</form>
			<script type='text/javascript'>InitiateSlideShow()</script>
			<?php
		}
		else
		{
			echo "Directory not exists (". $easyimage_location.")";
		}
	}
	else
	{
		echo "No record found for the Id = ". $easyimage_widget;
	}
}

function easyimage_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". wp_easy_table . "'") != wp_easy_table) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". wp_easy_table . "` (";
		$sSql = $sSql . "`easyimage_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`easyimage_location` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`easyimage_width` VARCHAR( 4 ) NOT NULL ,";
		$sSql = $sSql . "`easyimage_height` VARCHAR( 4 ) NOT NULL ,";
		$sSql = $sSql . "`easyimage_random` VARCHAR( 4 ) NOT NULL ,";
		$sSql = $sSql . "`easyimage_date` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( `easyimage_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO `". wp_easy_table . "` (`easyimage_location`, `easyimage_width`, `easyimage_height`, `easyimage_random` , `easyimage_Date`)"; 
		$sSql = $IsSql . " VALUES ('wp-content/plugins/easy-image-slideshow/images/', '200', '150', 'YES', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO `". wp_easy_table . "` (`easyimage_location`, `easyimage_width`, `easyimage_height`, `easyimage_random` , `easyimage_Date`)"; 
		$sSql = $IsSql . " VALUES ('wp-content/plugins/easy-image-slideshow/images1/', '336', '280', 'YES', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);

	}
	add_option('easyimage_title', "Easy image slideshow");
	add_option('easyimage_widget', "1");
}

function easyimage_admin_options() 
{
	include_once("image-management.php");
}

function easyimage_shortcode( $atts ) 
{
	global $wpdb;
	//[easy-image-slideshow id="2"]

	$easyimage = "";
	
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$easyimage_widget = $atts['id'];
	
	$url = easyimage_plugin_url('inc');
	$siteurl = get_option('siteurl') . "/";
	
	$sSql = "select * from ".wp_easy_table." where 1 = 1";

	if(is_numeric($easyimage_widget))
	{ 
		$sSql = $sSql . " and easyimage_id=$easyimage_widget";
	}

	$sSql = $sSql . " order by rand() limit 0,1;";
	
	$easyimage = "";
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$data = $data[0];
		$easyimage_location = $data->easyimage_location; 
		$easyimage_width = $data->easyimage_width; 
		$easyimage_height = $data->easyimage_height; 
		$easyimage_random = $data->easyimage_random; 
		
		if(is_dir($easyimage_location))
		{
			$f_dirHandle = opendir($easyimage_location);
			$path = "";
			while ($f_file = readdir($f_dirHandle)) 
			{
				$f_file = strtoupper($f_file);
				$f_file_nocase = $f_file;
				if(!is_dir($f_file) && (strpos($f_file, '.JPG')>0 or strpos($f_file, '.GIF')>0 or strpos($f_file, '.PNG')>0)) 
				{
					$path = $path . "'" . $siteurl . $easyimage_location . $f_file_nocase . "',";
				}
			}
			closedir($f_dirHandle);
			$path = substr($path,0,(strlen($path)-1));
			
			$easyimage .= "<script type='text/javascript'> ";
				$easyimage .= "var SimpleSlides = new Array(".$path."); ";
			$easyimage .= "</script> ";
			$easyimage .= '<form name="FormSlideShow"> ';
				$easyimage .= '<div style="padding-bottom:5px;width:'.$easyimage_width.'px;"><img name="EasyImage" id="EasyImage" width="'.$easyimage_width.'" height="'.$easyimage_height.'" /></div> ';
				$easyimage .= '<div style="width:'.$easyimage_width.'px;height:10px;"> ';
					$easyimage .= '<span style="float:left;"><img name="Previous" id="Previous" style="cursor:pointer;" src="'.$url.'/left.jpg" onClick="SlideShowMoment(-1)" /></span> ';
					$easyimage .= '<span style="float:right;"><img name="Next" id="Next" style="cursor:pointer;" src="'.$url.'/right.jpg" onClick="SlideShowMoment(1)" /></span> ';
				$easyimage .= "</div> ";
			$easyimage .= "</form> ";
			$easyimage .= "<script type='text/javascript'>InitiateSlideShow()</script>";
			
		}
		else
		{
			$easyimage = "Directory not exists (". $easyimage_location.")";
		}
	}
	else
	{
		$easyimage = "No record found for the Id = ". $easyimage_widget;
	}
	return $easyimage;
}

function easyimage_deactivation() 
{

}

function easyimage_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'easy-image-slideshow', get_option('siteurl').'/wp-content/plugins/easy-image-slideshow/easy-image-slideshow.js');
	}	
}

function easyimage_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page('Easy image slideshow', 'Easy slideshow', 'manage_options', __FILE__, 'easyimage_admin_options' );
	}
}

function easyimage_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('easyimage_title');
	echo $after_title;
	easyimage();
	echo $after_widget;
}

function easyimage_control() 
{
	$easyimage_widget = get_option('easyimage_widget');
	if (@$_POST['easyimage_submit']) 
	{
		$easyimage_widget = $_POST['easyimage_widget'];
		update_option('easyimage_widget', $easyimage_widget );
	}
	
	echo '<p>Id:<br><input  style="width: 200px;" type="text" value="';
	echo $easyimage_widget . '" name="easyimage_widget" id="easyimage_widget" /></p>';
	echo '<input type="hidden" id="easyimage_submit" name="easyimage_submit" value="1" />';
}

function easyimage_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Easy image slideshow', 'Easy image slideshow', 'easyimage_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Easy image slideshow', array('Easy image slideshow', 'widgets'), 'easyimage_control');
	} 
}

add_shortcode( 'easy-image-slideshow', 'easyimage_shortcode' );
add_action('wp_enqueue_scripts', 'easyimage_add_javascript_files');
add_action('admin_menu', 'easyimage_add_to_menu');
add_action("plugins_loaded", "easyimage_init");
register_activation_hook(__FILE__, 'easyimage_install');
register_deactivation_hook(__FILE__, 'easyimage_deactivation');
?>
