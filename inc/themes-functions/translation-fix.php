<?php
/*
* Update the plugin global variable 
*/
//add_action('init', 'change_trp_language', 1001);
function change_trp_language() {
	global $TRP_LANGUAGE;
 	if ($TRP_LANGUAGE == 'en_US') {
 		$TRP_LANGUAGE = 'en_GB';
 	}	
}

/*
* Translate the content again in en_uk
*/
add_filter( 'wpseo_opengraph_title', 'trp_re_translate_the_content', 1001, 1 );
add_filter( 'wpseo_opengraph_desc', 'trp_re_translate_the_content', 1001, 1 );
add_filter( 'wpseo_title', 'trp_re_translate_the_content', 1001, 1 );
add_filter( 'wpseo_metadesc', 'trp_re_translate_the_content', 1001, 1 );
add_filter( 'the_title', 'trp_re_translate_the_content', 1001, 1 );
add_filter( 'the_content', 'trp_re_translate_the_content', 1001, 1 );
function trp_re_translate_the_content($content) {
	global $TRP_LANGUAGE;
	if (($TRP_LANGUAGE == 'en_US') || ($TRP_LANGUAGE == 'en_GB')) {
		$content = trp_get_translated_content($content);
	}
	return $content;
}

/*
* Get the translated content with the plugin
*/
function trp_get_translated_content($content){
    if (class_exists('TRP_Translation_Render')){
    	global $TRP_LANGUAGE;
    	if ($TRP_LANGUAGE == 'en_US') {
	 		$TRP_LANGUAGE = 'en_GB';
	 	}	
        $trp = TRP_Translate_Press::get_trp_instance();
        $render = $trp->get_component('translation_render');
        $content = $render->translate_page($content);
        $TRP_LANGUAGE = 'en_US';
    }
    return $content;
}