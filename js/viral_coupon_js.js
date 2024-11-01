jQuery(document).ready(function(){
	jQuery(".ino_input input").click(function(){
				jQuery(this).css("background-color","white");
	});
	jQuery(".ino_input input").on('blur',function(){
				jQuery(this).css("background-color","#ececec");
	});
	jQuery(".ino_input textarea").click(function(){
				jQuery(this).css("background-color","white");
	});
	jQuery(".ino_input textarea").on('blur',function(){
				jQuery(this).css("background-color","#ececec");
	});
	jQuery(".ino_input select").click(function(){
				jQuery(this).css("background-color","white");
	});
	jQuery(".ino_input select").on('blur',function(){
				jQuery(this).css("background-color","#ececec");
	});	
});

		
			
