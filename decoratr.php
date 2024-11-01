<?php
/*
Plugin Name: WP Decoratr
Plugin URI: http://blinger.org/wordpress-plugins/wp-decoratr/
Description: Automatically finds images from Flickr related to your post content.
Version: 1.4
Author: iDope
Author URI: http://efextra.com/
*/

/*  Copyright 2008  Saurabh Gupta  (email : saurabh0@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Flickr.com API key
define('FLICKR_API_KEY', '6833ce889f21fffcaa88f2aaabbea4f4');
// Flicker.com CC licences. Multiple license may be comma-separated. Leave blank to return all images (not recommended).
define('DECORATR_LICENSES_DEFAULT', '1,2,3,4,5,6,7');
// The HTML template used for inserting the image (You can also edit the styles in wp-decoratr/style.css)
define('DECORATR_TEMPLATE_DEFAULT', '<span class="wp-decoratr-image"><img src="[image-src]" alt="[image-title]" /><br /><a href="[image-link]" rel="external nofollow">Photo by [image-owner]</a></span>');
// Specify an IP address other than server's default IP for making API calls (optional)
//define('INTERFACE_IP', 'x.x.x.x');

// Add decoratr css (comment the line below if you are using your own styles)
add_action( 'wp_head', 'decoratr_wp_head' );
function decoratr_wp_head() {
	echo '<link rel="stylesheet" href="' . get_option('siteurl') . '/wp-content/plugins/wp-decoratr/style.css" type="text/css" />'."\n";
}

// Add settings link to the plugin page
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'decoratr_add_action_links' );
function decoratr_add_action_links( $links ) { 
	$link = '<a href="options-general.php?page=decoratr">Settings</a>'; 
	array_unshift( $links, $link ); 
	return $links; 
}

// Add admin menu for settings
add_action('admin_menu', 'decoratr_add_option_page');
function decoratr_add_option_page() {
    // Add a new submenu under options:
    add_options_page('Decoratr', 'Decoratr', 'edit_themes', 'decoratr', 'decoratr_options_page');
}

function decoratr_options_page() {
	if(isset($_POST['decoratr_action_save'])) {
		update_option('decoratr_licenses',$_POST['decoratr_licenses']);
		update_option('decoratr_template',$_POST['decoratr_template']);
		echo "<div id='message' class='updated fade'><p>Decoratr settings saved.</p></div>";
    }
	else if(isset($_POST['decoratr_action_defaults'])) {
		delete_option('decoratr_licenses');
		delete_option('decoratr_template');
		echo "<div id='message' class='updated fade'><p>Decoratr default settings loaded.</p></div>";
	}
	$decoratr_licenses = get_option('decoratr_licenses', explode(',', DECORATR_LICENSES_DEFAULT));
	$decoratr_template = get_option('decoratr_template', DECORATR_TEMPLATE_DEFAULT);
	if(empty($decoratr_template)) $decoratr_template = DECORATR_TEMPLATE_DEFAULT;
	//global $shortcode_tags; print_r($shortcode_tags);
    ?>
	<div class="wrap"><h2>Decoratr Settings</h2>
	<form name="site" action="" method="post" id="decoratr-form">

	<div>
	<fieldset>
	<legend><b><?php _e('Advanced Settings') ?></b></legend>

	<table style="width: 100%">
		<tr>
			<td style="width: 150px; vertical-align:top;"><label for="decoratr-licenses">Image Licenses:</label></td>
			<td>
				<input type="checkbox" name="decoratr_licenses[]" value="1" <?php echo in_array('1', $decoratr_licenses)?'checked="checked"':''; ?> id="cc1" /><label for="cc1"> Attribution-NonCommercial-ShareAlike License</label> [<a href="http://creativecommons.org/licenses/by-nc-sa/2.0/" target="_blank">info</a>]<br />
				<input type="checkbox" name="decoratr_licenses[]" value="2" <?php echo in_array('2', $decoratr_licenses)?'checked="checked"':''; ?> id="cc2" /><label for="cc2"> Attribution-NonCommercial License</label> [<a href="http://creativecommons.org/licenses/by-nc/2.0/" target="_blank">info</a>]<br />
				<input type="checkbox" name="decoratr_licenses[]" value="3" <?php echo in_array('3', $decoratr_licenses)?'checked="checked"':''; ?> id="cc3" /><label for="cc3"> Attribution-NonCommercial-NoDerivs License</label> [<a href="http://creativecommons.org/licenses/by-nc-nd/2.0/" target="_blank">info</a>]<br />
				<input type="checkbox" name="decoratr_licenses[]" value="4" <?php echo in_array('4', $decoratr_licenses)?'checked="checked"':''; ?> id="cc4" /><label for="cc4"> Attribution License</label> [<a href="http://creativecommons.org/licenses/by/2.0/" target="_blank">info</a>]<br />
				<input type="checkbox" name="decoratr_licenses[]" value="5" <?php echo in_array('5', $decoratr_licenses)?'checked="checked"':''; ?> id="cc5" /><label for="cc5"> Attribution-ShareAlike License</label> [<a href="http://creativecommons.org/licenses/by-sa/2.0/" target="_blank">info</a>]<br />
				<input type="checkbox" name="decoratr_licenses[]" value="6" <?php echo in_array('6', $decoratr_licenses)?'checked="checked"':''; ?> id="cc6" /><label for="cc6"> Attribution-NoDerivs License</label> [<a href="http://creativecommons.org/licenses/by-nd/2.0/" target="_blank">info</a>]<br />
				<input type="checkbox" name="decoratr_licenses[]" value="7" <?php echo in_array('7', $decoratr_licenses)?'checked="checked"':''; ?> id="cc7" /><label for="cc7"> No known copyright restrictions</label> [<a href="http://flickr.com/commons/usage/" target="_blank">info</a>]<br />
				<span class="setting-description">Select the Creative Commons licenses under which the pictures should be available.</span><br /><br />
			</td>
		</tr>
		<tr>
			<td style="width: 150px; vertical-align:top;"><label for="decoratr-template">Image Template:</label></td>
			<td>
				<textarea name="decoratr_template" id="decoratr-template" rows="5" style="width: 500px"><?php echo attribute_escape($decoratr_template); ?></textarea><br />
				<span class="setting-description">The HTML template used for inserting the image (You can also edit the styles in wp-decoratr/style.css).<br />
					You can use the following variables in the template: <code>[image-src], [image-link], [image-title], [image-owner]</code></span>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="submit">
				<input name="decoratr_action_save" id="decoratr-action-save" type="submit" style="font-weight: bold;" value="Save Settings" />
				<input name="decoratr_action_defaults" id="decoratr-action-defaults" type="submit" value="Default Settings" />
			</td>
		</tr>
	</table>
	
	</fieldset>
	</div>
	</form>
	<small></small>
	</div>
	<?php
}

add_action('admin_print_scripts', 'insert_decoratr_scripts' );
function insert_decoratr_scripts()
{
	// use JavaScript SACK library for Ajax
	wp_print_scripts( array( 'sack' ));
}

add_action('admin_init', 'decoratr_addbuttons');
function decoratr_addbuttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'add_decoratr_tinymce_plugin');
		add_filter('mce_buttons', 'register_decoratr_button');
	}
}
function register_decoratr_button($buttons) {
   array_push($buttons, '|', 'btnDecoratr');
   return $buttons;
}
function add_decoratr_tinymce_plugin($plugin_array) {
   $plugin_array['Decoratr'] = get_option( 'siteurl' ) . '/wp-content/plugins/wp-decoratr/editor_plugin.js';
   return $plugin_array;
}

// Add Decoratr scripts to the post and page editing forms
add_action('edit_form_advanced','insert_decoratr');
add_action('edit_page_form','insert_decoratr');
function insert_decoratr() {
	global $post_ID;
	$decoratr_template = get_option('decoratr_template', DECORATR_TEMPLATE_DEFAULT);
	if(empty($decoratr_template)) $decoratr_template = DECORATR_TEMPLATE_DEFAULT;
	?>
<script type="text/javascript">
    //<![CDATA[
	jQuery(document).ready( function() {
		jQuery( '#postdivrich' ).after( jQuery( '#decoratrdiv' ) );
		jQuery( '#ed_toolbar' ).append( jQuery( '#decoratrgetimages' ) );
	} );

	var images;
	var siteurl='<?php bloginfo( "wpurl" ); ?>';
	function decoratr_get_images(){
		if ((typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
			jQuery('#content_btnDecoratr > img').attr("src",siteurl + "/wp-content/plugins/wp-decoratr/ajax-loader.gif");
			tinyMCE.editors.content.controlManager.controls.content_btnDecoratr.setDisabled(true);
			tinyMCE.triggerSave();
		}
		if(jQuery('#title').val().length==0 || jQuery('#content').val().length==0) {
			alert("Please enter some content first");
			return;
		}
		jQuery('#decoratrimages').html('<img src="' + siteurl + '/wp-content/plugins/wp-decoratr/ajax-loader.gif" width="16" height="16" />');
		jQuery('#decoratrgetimages').attr('disabled','disabled');
		var mysack = new sack(siteurl + "/wp-admin/admin-ajax.php" );
		mysack.execute = 1;
		mysack.method = 'POST';
		mysack.setVar( "action", "decoratr_get_images" );
		mysack.setVar( "title", jQuery('#title').val() );
		mysack.setVar( "content", jQuery('#content').val() );
		mysack.encVar( "cookie", document.cookie, false );
		mysack.setVar( "tags", jQuery('#tags-input').length > 0 ? jQuery('#tags-input').val() : ''); // '#tags-input' doesn't exist for page editing
		mysack.onError = function() { alert('AJAX error in getting images') };
		mysack.runAJAX();
		return true;
	}
	
	function decoratr_got_images(pics,tags){
		images=pics;
		var result="<fieldset style=\"border: 1px solid gray\"><legend>Tags</legend>"+tags+"</fieldset><fieldset style=\"border: 1px solid gray\"><legend>Images (" + images.length + ")</legend>";
		result+="<table><tr><td><select id='decoratrimagesize'>" + 
		"<option value='_s'>small square (75x75)</option>" + 
		"<option value='_t'>thumbnail (100 on longest side)</option>" + 
		"<option value='_m' selected='selected'>small (240 on longest side)</option>" + 
		"<option value=''>medium (500 on longest side)</option>" + 
		"<option value='_b'>large (1024 on longest side)</option>" + 
		"</select></td><td>Click any image to insert the selected size in the post.</td></tr></table>";
		for (var i = 0; i < images.length; i++) {
			if (images[i]['id'] == 'tag') {
				result += "<h3 style=\"border-bottom: 1px solid gray\">" + images[i]['title'] + " (" + images[i]['mode'] + ")</h3>\n";
			}
			else {
				result += "<img src='http://farm" + images[i]['farm'] + ".static.flickr.com/" + images[i]['server'] + "/" + images[i]['id'] + "_" + images[i]['secret'] + "_s.jpg' alt='" + images[i]['title'] + "'  onclick='insert_image(" + i + ")' />\n";
			}
		}
		result+="</fieldset>";
		jQuery('#decoratrimages').html(result);
		jQuery('#decoratrgetimages').removeAttr('disabled');
		if ((typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
			jQuery('#content_btnDecoratr > img').attr("src",siteurl + "/wp-content/plugins/wp-decoratr/decoratr.gif");
			tinyMCE.editors.content.controlManager.controls.content_btnDecoratr.setDisabled(false);
		}
	}
	function decoratr_error(msg){
		alert(msg);
		jQuery('#decoratrimages').html('<pre class="error fade">' + msg + '</pre>');
		jQuery('#decoratrgetimages').removeAttr('disabled');
		if ((typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
			jQuery('#content_btnDecoratr > img').attr("src",siteurl + "/wp-content/plugins/wp-decoratr/decoratr.gif");
			tinyMCE.editors.content.controlManager.controls.content_btnDecoratr.setDisabled(false);
		}
	}
	function insert_image(i){
		var imgSize=document.getElementById('decoratrimagesize').value;
		var imgField = document.getElementById('content');
		var imgHtml = '<?php echo decoratr_ajax_escape($decoratr_template); ?>';
		imgHtml = imgHtml.replace('[image-link]', 'http://www.flickr.com/photos/' + images[i]['owner'] + '/' + images[i]['id']);
		imgHtml = imgHtml.replace('[image-src]', 'http://farm' + images[i]['farm'] + '.static.flickr.com/' + images[i]['server'] + '/' + images[i]['id'] + '_' + images[i]['secret'] + imgSize + '.jpg');
		imgHtml = imgHtml.replace('[image-title]', images[i]['title']);
		imgHtml = imgHtml.replace('[image-owner]', images[i]['ownername']);
    	if( (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden() ) {
			tinyMCE.execCommand("mceInsertContent",false,imgHtml);		
		} else {
			insert_text(imgField, imgHtml);
		}
		return false;
	}
    
    function insert_text(myField, myValue){
        //IE support
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
        }
        //MOZILLA/NETSCAPE support
        else 
            if (myField.selectionStart || myField.selectionStart == '0') {
                var startPos = myField.selectionStart;
                var endPos = myField.selectionEnd;
                myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
            }
            else {
                myField.value += myValue;
            }
    }
    //]]>
</script>
      	<div id="decoratrdiv" class="postbox">
      		<h3>WP Decoratr</h3>
	        <div id="decoratrimages" class="inside" style="text-align: center">Click the "Decoratr" button in the editor toolbar to find images related to your post.</div>
			<input type="button" id="decoratrgetimages" onclick="decoratr_get_images();return false;" class="ed_button" title="Get Images" value="decoratr" />
		</div>
<?php	
}

add_action('wp_ajax_decoratr_get_images', 'decoratr_get_images' );
function decoratr_get_images() {
	// Filter HTML
	$content=preg_replace('|<[^<>]*>|',' ',"{$_POST['title']}\n{$_POST['content']}");
	// Filter extra whitespace
	$content=preg_replace('|\s{2,}|',' ',$content);
	if(strlen($_POST['title'])) {
		$subject=$_POST['title'];
	} else {
		$subject=$_POST['tags'];
    }
	$postdata = array('appid'=>'WPDecoratr','context'=>$content,'query'=>$subject,'output'=>'php');
	if(!function_exists('curl_init')) decoratr_die('Error: cURL not available');
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://search.yahooapis.com/ContentAnalysisService/V1/termExtraction');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	if(defined('INTERFACE_IP'))
		curl_setopt($ch, CURLOPT_INTERFACE, INTERFACE_IP);
	$response = curl_exec($ch);
	if(curl_errno($ch)) decoratr_die(curl_error($ch));
	curl_close($ch);
	// Unserialize php data
    $results=unserialize($response); 
	if(!is_array($results) || !isset($results['ResultSet'])) {
		decoratr_die("API Error:\n\nRequest:\n".print_r($postdata,true)."\n\nResponse:\n$response");
	}
	// Get existing tags
	$tags = empty($_POST['tags']) ? array() : explode(',',$_POST['tags']);
	if(isset($results['ResultSet']['Result'])) {
		// Merge with existing tags
		$tags=array_merge($tags, (array)$results['ResultSet']['Result']);	
	}
	// Exit if we have no keywords to search
	if(count($tags) == 0) {
		decoratr_die('No keywords found! Try adding some content or tags.');
	}
	// Trim 
	array_walk($tags,create_function('&$value','$value = strtolower(trim($value));'));
	// Remove duplicates
	$tags = array_unique($tags);
	// Remove blanks
	if(in_array('',$tags)) unset($tags[array_search('',$tags)]);

	// Flickr stuff
	$json = array();
	foreach($tags as $tag) {
		// Tag search
		$response=decoratr_flickr_search($tag,'tags','relevance');
		if($response===false) die("alert('Error: Cannot connect to Flickr')");
		if(preg_match('|"photo":\[({.+})\]|',$response,$match))
			$json[]='{"id":"tag", "title":"'.decoratr_ajax_escape($tag).'","mode":"tag"},' . $match[1];
		// Check total number of results
		preg_match('|"total":"(\d+)"|',$response,$match);
		if(intval($match[1])<50) { // For less than 10 results try text search
			// Free text search
			$response=decoratr_flickr_search($tag,'text','relevance',25);
			if($response===false) die("alert('Error: Cannot connect to Flickr')");
			if(preg_match('|"photo":\[({.+})\]|',$response,$match))
				$json[]='{"id":"tag", "title":"'.decoratr_ajax_escape($tag).'","mode":"text search"},' . $match[1];
		}
	}
	$json=implode(',',$json);
	$json=preg_replace('/"(?:ispublic|isfriend|isfamily)":\d,\s+/', '', $json); // Remove stuff we don't need
	die("decoratr_got_images([$json],'".decoratr_ajax_escape(implode(', ',$tags))."')");
}


/**
* Searches Flickr as per the given parameters
*
* @param  string	$keyword		Keyword to search for
* @param  string	$mode			'tags' to search tags or 'text' for free text search
* @param  integer	$count			Number of results to return
* @param  integer	$sort			date-posted-asc, date-posted-desc, date-taken-asc, date-taken-desc, interestingness-desc, interestingness-asc, or relevance
* @return string 					Results in JSON format
*/
function decoratr_flickr_search($keyword,$mode='tags',$sort='interestingness-desc',$count=50) {
	$decoratr_licenses = implode(',', get_option('decoratr_licenses', explode(',', DECORATR_LICENSES_DEFAULT)));
	$request = "http://api.flickr.com/services/rest/?api_key=" . FLICKR_API_KEY . "&license=$decoratr_licenses&method=flickr.photos.search&format=json&tag_mode=all&extras=owner_name&per_page=$count&sort=$sort&$mode=" . urlencode($keyword);
	$result=@file_get_contents($request);
	return $result;
}

function decoratr_ajax_escape($str)
{
    $str = str_replace(array('\\', "'"), array("\\\\", "\\'"), $str);
    $str = preg_replace('#([\x00-\x1F])#e', '"\x" . sprintf("%02x", ord("\1"))', $str);

    return $str;
}

function decoratr_die($str)
{
	$str = decoratr_ajax_escape($str);
	die("decoratr_error('$str')");
}
?>
