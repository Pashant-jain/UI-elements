<?php 

add_shortcode( 'gallery_world', 'gallery');
function gallery() {
	
ob_start();

$args = array(  
        'post_type' => 'world_gallery',
        'post_status' => 'publish'
      
    );

    $loop = new WP_Query( $args ); 
 ?>
<section class="world-gallery">

<?php 
if ( $loop->have_posts() ) :
        while ( $loop->have_posts() ) : $loop->the_post(); ?>

  
   <div class="col-md-4">
      <a href="<?php the_permalink(); ?>">
         <div class="img-box"></div>
         <div class="img-card">
		 <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full');?>" alt="Fire Goats Battle Boats"></div>
      </a>
      <div class="img-overlay">
         <div class="">
            <a class="Card" href="<?php the_permalink(); ?>"><?php the_title();?></a>
            <div class="">
               <div class="user_info">By&nbsp;<span>
			   <a class="" href="<?php the_permalink(); ?>"><?php the_author(); ?></a></span></div>
            </div>
         </div>
      </div>
   </div>  
    
   <?php 
	endwhile; 
	endif;	?>
</section>
<?php 

wp_reset_postdata(); 

return ob_get_clean();
}

	?>