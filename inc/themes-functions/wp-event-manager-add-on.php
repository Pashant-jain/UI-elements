<?php

/*
* Form to add avatars from the frontend
*/
add_shortcode('wp_avatars_upload', 'wp_avatars_upload');
function wp_avatars_upload() {
	ob_start();
	?>
	<div class="avatars-upload-from">
		<form class="avatar-upload">
			<div class="row">
			<div class="col-12 col-sm-12">		
				<label class="custom-file-label" for="customFile">Choose avatar image</label>
			</div>
			  <div class="col-12 col-sm-6">
			  	<div class="form-group">
					<input type="file" class="form-control" id="customFile" />
			  	</div>	
			  </div>
		      <div class="col-12 col-sm-6">
			    <button class="btn btn-primary">Upload Avatar</button>
		  	  </div>
			</div>
		</form>
	</div>
	<?php
	return ob_get_clean();
}

/*
* Shortcode for displaying avatars page.
*/
add_shortcode( 'wp_avatars', 'wp_avatars');
function wp_avatars( $atts ) {
	extract( shortcode_atts( array(
		'nums' => '17',
	), $atts ) );
	
	ob_start();
	$args     = array(
		'post_type'           => 'attachment',
		'post_status'         => 'any',
    	'posts_per_page'      => $nums,
		'orderby'             => 'date',
		'order'               => 'desc',
		'meta_query' => array(
           array(
               'key' => 'is_this_avatar',
               'value' => 'Yes'
           ),
    	),
		'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png' 
	);

	$avatars = new WP_Query( $args );
	?>
	<div class="avatar-cards">
		<?php
		if($avatars->have_posts()): 
			while($avatars->have_posts()):
				$avatars->the_post();
				$attachment_id = get_the_ID();
				$image_url = wp_get_attachment_url($attachment_id);
				$caption = wp_get_attachment_caption($attachment_id);
				if ($caption=='') {
					$caption = 'Avatars';
				}
				?>
				<div class="avatar-card">
					<div class="avatar-card-body">
						<a class="custom-fancybox"  src="<?php  echo $image_url; ?>">
							<img src="<?php  echo $image_url; ?>" alt="" />
						</a>
						<h3><?php echo $caption; ?></h3>
					</div>			
				</div>
				<?php
			endwhile;
			wp_reset_query();
		endif;
		?>
	</div>
	<?php
	return ob_get_clean();
}

/*
* Shortcode for displaying events on page.
*/
add_shortcode( 'wp_featured_events', 'wp_featured_events');
function wp_featured_events( $atts ) {
	extract( shortcode_atts( array(
		'nums' => '4',
		'words' => '15',
		'height' => '192'
	), $atts ) );

	ob_start();
	$today =  date("Y-m-d");
	

	$args     = array(
		'post_type'           => 'event_listing',
		'post_status'         => array( 'any' ),
        'post__in' => get_option( 'sticky_posts' ),
        'ignore_sticky_posts' => 1,
		'posts_per_page'      => $nums,
		'meta_key'             => '_event_start_date',
		'orderby'             => 'meta_value',
		'meta_query' => array(
           array(
               'key' => '_featured',
               'value' => '1'
           ),
           array(
	            'key' => '_event_start_date',
	            'value' => $today,
	            'compare' => '>='
           ),
    	),
		'order'               => 'asc',
	);

	$events = new WP_Query( $args );
	?>
	<div class="wpel-cards">
		<?php
		if($events->have_posts()): 
			while($events->have_posts()):
				$events->the_post();
				$start_date 	= formatEventDate(get_post_meta(get_the_ID(), '_event_start_date', true));
				$end_date 		= formatEventDate(get_post_meta(get_the_ID(), '_event_end_date', true));
				$event_date 	= $start_date . ' - ' . $end_date;
				
				$start_time 	= get_post_meta(get_the_ID(), '_event_start_time', true);
				$end_time 		= get_post_meta(get_the_ID(), '_event_end_time', true);
				$time_zone 		= get_post_meta(get_the_ID(), '_time_zone', true);
				$event_time 	= $start_time . ' - ' . $end_time . ' ' . $time_zone;
				?>
				<div class="wpel-card">
					<a href="<?php the_permalink(); ?>" class="wpel-card-link"></a>
					<div class="wpel-card-heading">
						<h2><?php echo $start_date; ?></h2>
						<div class="wpel-time"><?php echo $event_time; ?></div>
					</div>	
					<div class="wpel-card-body">
						<h3><?php the_title(); ?></h3>
						<p><?php echo wp_trim_words(get_the_content(), $words); ?>...</p>
					</div>			
				</div>
				<?php
			endwhile;
			wp_reset_query();
		endif;
		?>
	</div>
	<?php
	return ob_get_clean();
}

function formatEventDate($date) {
	if($date) {
		$dateFormat 	= get_option( 'date_format' );
		$currentDate 	= strtotime(date("Y-m-d"));
		$startDate 		= strtotime($date);
		$datediff 		= $startDate - $currentDate;
		$difference 	= floor($datediff/(60*60*24));

		$currentWeek 	= date('W');
		$week 			= date('W', $startDate);
		$year 			= date('Y', $startDate);
		$currentYear 	= date('Y');
		
		if ($year == $currentYear) {
			if ($difference == 0) {
				$date = 'Today';
			} else if (($difference > 0) && ($currentWeek == $week)) {
				$date = date('l', strtotime($date));
			} else {
				$date = date('F j', strtotime($date));
			}
		} else {
			$date = date($dateFormat, strtotime($date));
		}
	}
	return $date;
}