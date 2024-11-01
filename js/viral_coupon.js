 FB.init();
 jQuery(document).ready(function(){
	 FB.Event.subscribe("edge.create", function(response) { 
		var fb="fb_cookie";
		var site=window.location.host;
		jQuery.get("http://"+site+"/wp-admin/admin-ajax.php", {fb_cookie:fb,action:"get_fb_cookie"}, function (data){
			 var json = jQuery.parseJSON(data);
			 //alert(json);
			 setCookie(c_name = "set_cookie", value = "fb_cookie", exdays = 2000);
			jQuery("#discount_form").submit();
		});	
	});	
});
function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	//alert("c_value: "+c_value);
	document.cookie=c_name + "=" + c_value;
	//alert(document.cookie);
}
	


