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

function easyimage_submit()
{
	if(document.easyimage_form.easyimage_location.value=="")
	{
		alert("Please enter the image folder (where you have your images).")
		document.easyimage_form.easyimage_location.focus();
		return false;
	}
	else if(document.easyimage_form.easyimage_width.value=="" || isNaN(document.easyimage_form.easyimage_width.value))
	{
		alert("Please enter width of the slideshow, only number.")
		document.easyimage_form.easyimage_width.focus();
		document.easyimage_form.easyimage_width.select();
		return false;
	}
	else if(document.easyimage_form.easyimage_height.value=="" || isNaN(document.easyimage_form.easyimage_height.value))
	{
		alert("Please enter height of the slideshow, only number.")
		document.easyimage_form.easyimage_height.focus();
		document.easyimage_form.easyimage_height.select();
		return false;
	}
}

function easyimage_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_easyimage_display.action="options-general.php?page=easy-image-slideshow/easy-image-slideshow.php&AC=DEL&DID="+id;
		document.frm_easyimage_display.submit();
	}
}	

function easyimage_redirect()
{
	window.location = "options-general.php?page=easy-image-slideshow/easy-image-slideshow.php";
}

function easyimage_help()
{
	window.open("http://www.gopipulse.com/work/2012/06/20/easy-image-slideshow-wordpress-plugin/");
}