<?php
add_filter('wp_get_attachment_image_src', 'update_url_with_cdn', 1);
function update_url_with_cdn($images) {
	if ($images) {
		$images['0'] = str_replace(get_bloginfo('wpurl'), get_field('cdn', 'option'), $images['0']);
	}
	return $images;
}