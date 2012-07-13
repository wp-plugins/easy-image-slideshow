<?php
/**
 *     Easy image slideshow
 *     Copyright (C) 2012  www.gopipulse.com
 *     http://www.gopipulse.com/work/2012/06/20/easy-image-slideshow-wordpress-plugin/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	
?>

<div class="wrap">
  <?php
  	global $wpdb;
    $title = __('Easy image slideshow');
    @$mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=easy-image-slideshow/easy-image-slideshow.php";
    @$DID=@$_GET["DID"];
    @$AC=@$_GET["AC"];
    @$submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['easyimage_location']) <>"")
    {
			if($_POST['easyimage_id'] == "" )
			{
					$sql = "insert into ".wp_easy_table.""
					. " set `easyimage_location` = '" . mysql_real_escape_string(trim($_POST['easyimage_location']))
					. "', `easyimage_width` = '" . mysql_real_escape_string(trim($_POST['easyimage_width']))
					. "', `easyimage_height` = '" . mysql_real_escape_string(trim($_POST['easyimage_height']))
					. "', `easyimage_random` = '" . mysql_real_escape_string(trim($_POST['easyimage_random']))
					. "'";	
			}
			else
			{
					$sql = "update ".wp_easy_table.""
					. " set `easyimage_location` = '" . mysql_real_escape_string(trim($_POST['easyimage_location']))
					. "', `easyimage_width` = '" . mysql_real_escape_string(trim($_POST['easyimage_width']))
					. "', `easyimage_height` = '" . mysql_real_escape_string(trim($_POST['easyimage_height']))
					. "', `easyimage_random` = '" . mysql_real_escape_string(trim($_POST['easyimage_random']))
					. "' where `easyimage_id` = '" . $_POST['easyimage_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".wp_easy_table." where easyimage_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".wp_easy_table." where easyimage_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $easyimage_id_x = htmlspecialchars(stripslashes($data->easyimage_id)); 
		if ( !empty($data) ) $easyimage_location_x = htmlspecialchars(stripslashes($data->easyimage_location)); 
		if ( !empty($data) ) $easyimage_width_x = htmlspecialchars(stripslashes($data->easyimage_width));
        if ( !empty($data) ) $easyimage_height_x = htmlspecialchars(stripslashes($data->easyimage_height));
		if ( !empty($data) ) $easyimage_random_x = htmlspecialchars(stripslashes($data->easyimage_random));
        $submittext = "Update Message";
    }
    ?>
  <h2>Easy image slideshow</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/easy-image-slideshow/inc/setting.js"></script>
  <form name="easyimage_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return easyimage_submit()"  >
    <table width="100%">
      
      
      <tr>
        <td width="28%" align="left" valign="middle">Width (only number):</td>
        <td width="72%" align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="easyimage_width" type="text" id="easyimage_width" value="<?php echo @$easyimage_width_x; ?>" maxlength="4" /></td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="left" valign="middle">Height (only number):</td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="easyimage_height" type="text" id="easyimage_height" value="<?php echo @$easyimage_height_x; ?>" maxlength="4" /></td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle">Random</td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><select style="width:130px;" name="easyimage_random" id="easyimage_random">
          <option value='YES' <?php if(@$easyimage_random_x=='YES') { echo 'selected' ; } ?>>YES</option>
          <option value='NO' <?php if(@$easyimage_random_x=='NO') { echo 'selected' ; } ?>>NO</option>
        </select></td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Image folder location</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">
        <input name="easyimage_location" type="text" id="easyimage_location" value="<?php echo @$easyimage_location_x; ?>" size="120" maxlength="1024" />
        <br /> Ex : wp-content/plugins/easy-image-slideshow/images/ <br />
             </td>
      </tr>
      <tr>
        <td height="45" colspan="3" align="left" valign="middle"><input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
          <input name="publish" lang="publish" class="button-primary" onClick="easyimage_redirect()" value="Cancel" type="button" />
		  <input name="Help" lang="publish" class="button-primary" onclick="easyimage_help()" value="Help" type="button" /></td>
      </tr>
      <input name="easyimage_id" id="easyimage_id" type="hidden" value="<?php echo @$easyimage_id_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".wp_easy_table." order by easyimage_id");
	if ( empty($data) ) 
	{ 
		//echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		//return;
	}
	?>
    <form name="frm_easyimage_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th align="left" scope="col">Id
              </th>
            <th scope="col">Location
              </th>
            <th scope="col">Width
              </th>
			<th scope="col">Height
              </th>
            <th align="left" scope="col">Action
              </th>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->easyimage_id)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->easyimage_location)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->easyimage_width)); ?></td>
			<td align="left" valign="middle"><?php echo(stripslashes($data->easyimage_height)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=easy-image-slideshow/easy-image-slideshow.php&DID=<?php echo($data->easyimage_id); ?>">Edit</a> &nbsp; <a onClick="javascript:easyimage_delete('<?php echo($data->easyimage_id); ?>')" href="javascript:void(0);">Delete</a></td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
      </table>
      <br />
      Check the official page for live demo and more help <a href="http://www.gopipulse.com/work/2012/06/20/easy-image-slideshow-wordpress-plugin/">click here</a>  <br />
	  Note: Don't upload your original images into plug-in folder. if you upload the images into plug-in folder, you may lose the images when you update the plug-in to next version.   
    </form>
  </div>
</div>
