<?php
// Form submitted, check the data
if (isset($_POST['frm_easyimage_display']) && $_POST['frm_easyimage_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
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
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
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
			$easyimage_success = __('Selected record was successfully deleted.', TinyCarousel_UNIQUE_NAME);
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
    <h2><?php echo easyimage_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=easy-image-slideshow&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".wp_easy_table."` order by easyimage_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/easy-image-slideshow/inc/setting.js"></script>
		<form name="frm_easyimage_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="easyimage_group_item[]" /></th>
			<th scope="col">Image folder</th>
			<th scope="col">Short code</th>
            <th scope="col">Slideshow Width</th>
			<th scope="col">Slideshow Height</th>
			<th scope="col">Random</th>
			<th scope="col">Id</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="easyimage_group_item[]" /></th>
			<th scope="col">Image folder</th>
			<th scope="col">Short code</th>
            <th scope="col">Slideshow Width</th>
			<th scope="col">Slideshow Height</th>
			<th scope="col">Random</th>
			<th scope="col">Id</th>
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
						<td align="left"><input type="checkbox" value="<?php echo $data['easyimage_id']; ?>" name="easyimage_group_item[]"></th>
						<td><?php echo $data['easyimage_location']; ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=easy-image-slideshow&amp;ac=edit&amp;did=<?php echo $data['easyimage_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:easyimage_delete('<?php echo $data['easyimage_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
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
				?><tr><td colspan="7" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('easyimage_form_show'); ?>
		<input type="hidden" name="frm_easyimage_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=easy-image-slideshow&amp;ac=add">Add New</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo easyimage_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget to your sidebar.</li>
		<li>Add the plugin in the posts or pages using short code.</li>
		<li>Add directly in to the theme using PHP code.</li>
	</ol>
	<p class="description"><?php echo easyimage_LINK; ?><br />Don't upload your original images into plug-in folder. if you upload the images into plug-in folder, you may lose the images when you update the plug-in to next version.</p>
	</div>
</div>