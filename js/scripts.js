
  
jQuery(document).ready(function($){

	// XANA sidebar updates
	if (jQuery(window).width() <= 768) {

		jQuery('.single-post.xana-pages .elementor-widget-sidebar .elementor-widget-container .widget-title').click(function(e){
			
			if (!jQuery('body').hasClass("xana-full-sidebar")) {
				jQuery('.xana-widget-close').remove();
				jQuery(this).closest('.elementor-widget-sidebar').prepend('<span class="xana-widget-close">&times</span>');
				jQuery('body').addClass('scrollOff');
				jQuery('body').addClass('xana-full-sidebar');
			}

			jQuery('.xana-widget-close').on('click',function(){
				jQuery('body').removeClass('scrollOff');
				jQuery('body').removeClass('xana-full-sidebar');
			});

		});
	
	}

   //Faq section 
	if(jQuery('.lcp_catlist li').hasClass('current')){
	    jQuery('.lcp_catlist').parent().closest('.sp-ea-single').removeClass('ea-expand');
    	jQuery('.lcp_catlist').parent().closest('.ea-header').addClass('collapsed');
    			   		jQuery('.lcp_catlist').parent().closest('.spcollapse').removeClass('show');

    jQuery('.current').parent().closest('.sp-ea-single').addClass('ea-expand');
    jQuery('.current').parent().closest('.ea-header').removeClass('collapsed');
    jQuery('.current').parent().closest('.spcollapse').addClass('show');
}

    // Change url for terms links
 	jQuery('.category-xigolo-terms a').each(function(){
        var newurl = jQuery(this).attr('href');
        pageUrl = 'https://site.xigolo.com/';
        pointedUrl = 'https://www.xigolo.com/';
        newurl = newurl.replace(pageUrl, pointedUrl);
        if (newurl) {
            jQuery(this).attr('href', newurl);
        }
    });

	jQuery('.slicknav_btn').on('click', function(){
		jQuery('.slicknav_menu').toggleClass('menuOpen');
		jQuery('.main-navigation-menu li.xana-login').toggle();
		jQuery('.main-navigation-menu li.xana-signup').toggle();
		jQuery('body').toggleClass('scrollOff'); 
	});

	jQuery('.slicknav_menu').on('click', function(e){
		if (e.target !== this)
    		return;
		
		jQuery('.slicknav_btn').click();
	});
    
	// Custom Fancy Box
	jQuery('.custom-fancybox').each(function(){
		jQuery(this).click(function(){
			jQuery('.custom-fancybox-box').remove();
			var imgurl = jQuery(this).attr('src');
			jQuery('body').append('<div class="custom-fancybox-box"><div class="card"><span>×</span><img src="'+imgurl+'" alt="" /></div></div>');
			jQuery('.custom-fancybox-box').fadeIn();
			jQuery('.custom-fancybox-box span').click(function(){
				jQuery(this).parent().parent().fadeOut().remove();
			});
			jQuery('.custom-fancybox-box').click(function(){
				console.log("calass", jQuery(this).attr('class'));
				jQuery(this).fadeOut().remove();
			}).children('.card').click(function(){
				return false;
			});
		});
	});

	// Android Download link update
	const urlParams = new URLSearchParams(window.location.search);
	const myLang = urlParams.get('lang');
	if ((myLang == 'zh') || (myLang == 'zh_tw')) {
		jQuery('.app_icons_inline a').each(function(){
			var newHref = jQuery(this).attr('zhhref');
			jQuery(this).attr('href', newHref);
		});
	}
    
    

});