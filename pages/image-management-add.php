<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$easyimage_errors = array();
$easyimage_success = '';
$easyimage_error_found = FALSE;

// Preset the form fields
$form = array(
	'easyimage_id' => '',
	'easyimage_location' => '',
	'easyimage_width' => '',
	'easyimage_height' => '',
	'easyimage_random' => ''
);

// Form submitted, check the data
if (isset($_POST['easyimage_form_submit']) && $_POST['easyimage_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('easyimage_form_add');
	
	$form['easyimage_location'] = isset($_POST['easyimage_location']) ? $_POST['easyimage_location'] : '';
	if ($form['easyimage_location'] == '')
	{
		$easyimage_errors[] = __('Please enter the image folder (where you have your images).', 'easy-image-slideshow');
		$easyimage_error_found = TRUE;
	}
	
	$form['easyimage_width'] = isset($_POST['easyimage_width']) ? $_POST['easyimage_width'] : '';
	if ($form['easyimage_width'] == '')
	{
		$easyimage_errors[] = __('Please enter width of the slideshow, only number.', 'easy-image-slideshow');
		$easyimage_error_found = TRUE;
	}
	
	$form['easyimage_height'] = isset($_POST['easyimage_height']) ? $_POST['easyimage_height'] : '';
	if ($form['easyimage_height'] == '')
	{
		$easyimage_errors[] = __('Please enter height of the slideshow, only number.', 'easy-image-slideshow');
		$easyimage_error_found = TRUE;
	}
	
	$form['easyimage_random'] = isset($_POST['easyimage_random']) ? $_POST['easyimage_random'] : '';


	//	No errors found, we can add this Group to the table
	if ($easyimage_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".wp_easy_table."`
			(`easyimage_location`, `easyimage_width`, `easyimage_height`, `easyimage_random`)
			VALUES(%s, %s, %s, %s)",
			array($form['easyimage_location'], $form['easyimage_width'], $form['easyimage_height'], $form['easyimage_random'])
		);
		$wpdb->query($sql);
		
		$easyimage_success = __('New details was successfully added.', 'easy-image-slideshow');
		
		// Reset the form fields
		$form = array(
			'easyimage_id' => '',
			'easyimage_location' => '',
			'easyimage_width' => '',
			'easyimage_height' => '',
			'easyimage_random' => ''
		);
	}
}

if ($easyimage_error_found == TRUE && isset($easyimage_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $easyimage_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($easyimage_error_found == FALSE && strlen($easyimage_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $easyimage_success; ?> 
		<a href="<?php echo EASYIMAGE_ADMIN_URL; ?>"><?php _e('Click here to view the details', 'easy-image-slideshow'); ?></a></strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo EASYIMAGE_PLUGIN_URL; ?>/inc/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Easy image slideshow', 'easy-image-slideshow'); ?></h2>
	<form name="easyimage_form" method="post" action="#" onsubmit="return easyimage_submit()"  >
      <h3><?php _e('Add details', 'easy-image-slideshow'); ?></h3>
      
		<label for="tag-title"><?php _e('Image folder location', 'easy-image-slideshow'); ?></label>
		<input name="easyimage_location" type="text" id="easyimage_location" value="" size="100" maxlength="1024" />
		<p>Example: wp-content/plugins/easy-image-slideshow/images/</p>
		
		<label for="tag-title"><?php _e('Slideshow width', 'easy-image-slideshow'); ?></label>
		<input name="easyimage_width" type="text" id="easyimage_width" value="200" maxlength="4" />
		<p><?php _e('Please enter width of the slideshow, only number.', 'easy-image-slideshow'); ?> (Ex: 200)</p>
		
		<label for="tag-title"><?php _e('Slideshow height', 'easy-image-slideshow'); ?></label>
		<input name="easyimage_height" type="text" id="easyimage_height" value="150" maxlength="4" />
		<p><?php _e('Please enter height of the slideshow, only number.', 'easy-image-slideshow'); ?> (Ex: 150)</p>
	  
		<label for="tag-title"><?php _e('Random display', 'easy-image-slideshow'); ?></label>
		<select name="easyimage_random" id="easyimage_random">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want to display images in random order?', 'easy-image-slideshow'); ?></p>
	  
      <input name="easyimage_id" id="easyimage_id" type="hidden" value="">
      <input type="hidden" name="easyimage_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Insert Details', 'easy-image-slideshow'); ?>" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="easyimage_redirect()" value="<?php _e('Cancel', 'easy-image-slideshow'); ?>" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="easyimage_help()" value="<?php _e('Help', 'easy-image-slideshow'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('easyimage_form_add'); ?>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'easy-image-slideshow'); ?>
	<a target="_blank" href="<?php echo EASYIMAGE_FAV; ?>"><?php _e('click here', 'easy-image-slideshow'); ?></a><br />
</p>
</div>