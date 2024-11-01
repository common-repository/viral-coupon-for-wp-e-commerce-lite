<?php
/*
Plugin Name: Viral Coupon for WP e-Commerce - Lite
Plugin URI: http://www.tychesoftwares.com/store/free-plugins/viral-coupon-for-wp-e-commerce-lite/
Description: This plugin allows customers to "Like" on Facebook to get discount during Checkout. The PRO version includes Twitter & Google+.
Author: Ashok Rane
Version: 1.0
Author URI: http://www.tychesoftwares.com/about
Contributor: Tyche Softwares, http://www.tychesoftwares.com/
*/

//add the js file to front side script.
function viral_coupon_front_scripts() {
	$path=get_bloginfo('url');
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/js/jquery.js'></script>";
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/js/widgets.js'></script>";
	print('<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>');
	print('<div id="fb-root"></div><script type="text/javascript">FB.XFBML.parse();</script>');
	print '<link rel="stylesheet" href="'.$path.'/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/css/viral_coupon.css" type="text/css" media="screen" /> ';
	
	$script = '<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".wpsc_coupon_row").hide();
			var value=jQuery(".entry-content").html();
			var trim_value=jQuery.trim(value);
			if(trim_value != "Oops, there is nothing in your cart."){
					var disc="discount";
					var coupone="coupone";
					jQuery("#checkout_page_container").before("<div id=disc><div id=coupone><div style=\"font-weight: bold; padding-top: 5px;\">'.get_option('message').'</div><fb:like class=\"fb-like\" href=\"'.str_replace(" ","",get_option("fburl")).'\" show_faces=\"false\" width=\"450\"></fb:like></div></div><div id=\"dis_cop\"></div> <div style=\"top: -1000px;\"><div style=\"position:absolute;top: -1000px;\" id=\"discount_coupone\"><form method=\"post\" action=\"\" id=\"discount_form\" name=\"discount_form\"><input type=\"text\" name=\"coupon_num\" id=\"coupon_num\" value=\"'.get_option('coupon').'\"><input type=\"hidden\" name=\"thanks\" value=\"thanks\"><input type=\"submit\" value=\"Update\"></form></div></div><input type=\"hidden\" value=\"'.get_option('show_facebook').'\" id=\"facebookStatus\">");
			}
		});
		</script>';
	if (isset($_POST['thanks'])){
		print'<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(".wpsc_coupon_row").hide();
				jQuery("#checkout_page_container").before("<div id=thank>'.get_option('uncouponed').'</div>");
			});
		</script>';	
	}else	if(get_option('cookies') == 'enable'){
		print $script;
	}else if (!isset($_COOKIE['set_cookie'])){
		print $script;
	}

	print "<script>var ajaxurl='".get_option("siteurl")."/wp-load.php"."'; </script>";
	print("<script type='text/javascript' src='".$path."/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/js/viral_coupon.js'></script>");
	
}
add_action ('wp_enqueue_scripts','viral_coupon_front_scripts');

add_action('admin_head', 'viral_coupon_adminside_script');
function viral_coupon_adminside_script() {
	$path=get_bloginfo('url');
	//print "<script type='text/javascript' src='".$path."/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/js/jquery.js'></script>";
	//print("<script type='text/javascript' src='".$path."/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/js/viral_coupon.js'></script>");
	print "<script type='text/javascript' src='".$path."/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/js/viral_coupon_js.js'></script>";
	print '<link rel="stylesheet" href="'.$path.'/wp-content/plugins/viral-coupon-for-wp-e-commerce-lite/css/viral_coupon_head.css" type="text/css" media="screen" /> ';
}  


add_action('wp_ajax_nopriv_get_fb_cookie', 'viral_coupon_action_get_fb');
add_action('wp_ajax_get_fb_cookie', 'viral_coupon_action_get_fb');
function viral_coupon_action_get_fb(){
	$p=$_COOKIE['set_cookie'];
	print json_encode($p);
	exit;
}

add_action('admin_menu', 'viral_coupon_menu');
function viral_coupon_menu()
{
	add_menu_page( 'Viral Coupon','Viral Coupon','administrator', 'viral_coupon','viral_coupon');
}

function viral_coupon(){
	
	if (isset($_POST['save']))
	{
		update_option('message',$_POST['message']);
		update_option('fburl',$_POST['fburl']);
		update_option('coupon',$_POST['coupon']);
		update_option('uncouponed',$_POST['uncouponed']);
		update_option('cookies',$_POST['cookies']);
		$show_facebook = "";
		
	} 	
	if (isset($_POST['save']))
	{
		echo "<div id='message' class='updated fade'>
			<p><strong>All Changes Are Succesfully Saved!</strong></p></div>";
	}
	echo "<form action='' method='post'>
			<div id='main'>
				<div class='ino_section'>								
					<div class='ino_titlee'><h3><span class='home'>Viral Coupon Settings</span></h3><div class='clearfix'></div></div>
					<div class='ino_optionss'>
				<div class='ino_input ino_textarea'>
					<label for='message'>Message</label>
					<textarea name='message' rows='3' cols='5' id='before_message'>".stripslashes(get_option('message'))."</textarea>
					<small >Enter the message you want to appear before the customer has liked or shared.</small><div class='clearfix'></div>
				</div>
				<div class='ino_input ino_text'>
					<label for='fburl'>Facebook URL</label>
					<input name='fburl' id='fburl' type='text' value='".get_option('fburl')."'>
					<small >Enter the URL you want to share on Facebook.</small><div class='clearfix'></div>
				</div>
				<div class='ino_input ino_select'>
					<label for='coupon' class='select-coupon-label'>Select Coupon code</label>
					<select name='coupon' id='cookies'>
						<option selected='selected'>".get_option('coupon')."</option>";
							global $wpdb; 
							$pre = $wpdb->prefix;
							$logs = $pre."wpsc_coupon_codes";
							$purchase_query = "SELECT coupon_code FROM $logs ";
							$purchase_results = $wpdb->get_results($purchase_query);
							$count=count($purchase_results);
							print_r($purchase_query);
							for($i=0;$i<$count;$i++){
								echo"<option>".$purchase_results[$i]->coupon_code."</option>";
							}					
					echo"</select>
					<small >Select Coupon code which should be applied</small><div class='clearfix'></div>
				</div>
				<div class='ino_input ino_textarea'>
					<label for='tweet' class='thankyou-message-label'>Thank you message</label>
					<textarea name='uncouponed' rows=''cols='5' id='text_thanks'>".stripslashes(get_option('uncouponed'))."</textarea>
					<small >Enter message to be shown when the user has liked or shared your link. You can included the coupon name here if you wan't your customer to be able to use it at another time.</small><div class='clearfix'></div>
				</div>
				<div class='ino_input ino_select' id='test_mode'>
					<label for='cookies'>Test Mode</label>
					<select name='cookies'>
						<option selected='selected'>".get_option('cookies')."</option>
						<option value='disable'>Disable</option>
						<option value='enable'>Enable</option>
					</select>
					<small >Enable if you don't want the Coupon box to dissapear after it has been liked/shared.</small><div class='clearfix'></div>
				</div>
			<div class='ino_foot'><span class='submit'><input type='submit' value='Save changes' name='save'></span></div></div>
		</form><p>&nbsp;</p><div id='pro-version' style='font-family:Verdana;margin-left:0px;'>
			<a target='_blank' href='http://www.tychesoftwares.com/store/premium-plugins/viral-coupon/'>Get your customers to share on Twitter, Google+. Purchase PRO version.</a>
		</div>";
}

?>
