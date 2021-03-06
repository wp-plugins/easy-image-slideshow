<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_easyimage_display']) && $_POST['frm_easyimage_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$easyimage_success = '';
	$easyimage_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".wp_easy_table."
		WHERE `easyimage_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'easy-image-slideshow'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('easyimage_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".wp_easy_table."`
					WHERE `easyimage_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$easyimage_success_msg = TRUE;
			$easyimage_success = __('Selected record was successfully deleted.', 'easy-image-slideshow');
		}
	}
	
	if ($easyimage_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $easyimage_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Easy image slideshow', 'easy-image-slideshow'); ?>
	<a class="add-new-h2" href="<?php echo EASYIMAGE_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'easy-image-slideshow'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".wp_easy_table."` order by easyimage_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo EASYIMAGE_PLUGIN_URL; ?>/inc/setting.js"></script>
		<form name="frm_easyimage_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Image Folder', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Short Code', 'easy-image-slideshow'); ?></th>
            <th scope="col"><?php _e('Slideshow Width', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Slideshow Height', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Random', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Id', 'easy-image-slideshow'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Image Folder', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Short Code', 'easy-image-slideshow'); ?></th>
            <th scope="col"><?php _e('Slideshow Width', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Slideshow Height', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Random', 'easy-image-slideshow'); ?></th>
			<th scope="col"><?php _e('Id', 'easy-image-slideshow'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td><?php echo $data['easyimage_location']; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo EASYIMAGE_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['easyimage_id']; ?>"><?php _e('Edit', 'easy-image-slideshow'); ?></a> | </span>
							<span class="trash"><a onClick="javascript:easyimage_delete('<?php echo $data['easyimage_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'easy-image-slideshow'); ?></a></span> 
						</div>
						</td>
						<td>[easy-image-slideshow id="<?php echo $data['easyimage_id']; ?>"]</td>
						<td><?php echo $data['easyimage_width']; ?></td>
						<td><?php echo $data['easyimage_height']; ?></td>
						<td><?php echo $data['easyimage_random']; ?></td>
						<td><?php echo $data['easyimage_id']; ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="6" align="center"><?php _e('No records available.', 'easy-image-slideshow'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('easyimage_form_show'); ?>
		<input type="hidden" name="frm_easyimage_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo EASYIMAGE_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'easy-image-slideshow'); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo EASYIMAGE_FAV; ?>"><?php _e('Help', 'easy-image-slideshow'); ?></a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3><?php _e('Plugin configuration option', 'easy-image-slideshow'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the widget to your sidebar.', 'easy-image-slideshow'); ?></li>
		<li><?php _e('Add the plugin in the posts or pages using short code.', 'easy-image-slideshow'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'easy-image-slideshow'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'easy-image-slideshow'); ?>
		<a target="_blank" href="<?php echo EASYIMAGE_FAV; ?>"><?php _e('click here', 'easy-image-slideshow'); ?></a><br />
		<?php _e('Dont upload your original images into plug-in folder. if you upload the images into plug-in folder, you may lose the images when you update the plug-in to next version.', 'easy-image-slideshow'); ?>
	</p>
	</div>
</div>