<?php 
function custom_excerpt_length( $length ) {
	return 15;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
add_shortcode( 'xana_blog_post', 'xana_blog');
function xana_blog() {
	
ob_start();

$args = array(  
        'post_type' => 'xana_blog',
        'post_status' => 'publish'
      
    );

    $loop = new WP_Query( $args ); 

if ( $loop->have_posts() ) :
while ( $loop->have_posts() ) : $loop->the_post(); ?>

   <article class="grid-post col-md-4">
   <div class="grid-post-holder">
      <div class="grid-post-holder-inner">
         <div class="entry-media">
         <a href="<?php the_permalink(); ?>">
            <div class="entry-thumbnail">
               <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full');?>" alt="">
            </div></a>
         </div>
         <div class="entry-wrapper">
            <header class="entry-header">
               <h2 class="entry-title"><a class="post-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
            </header>
            <div class="entry-content">
               <div class="grid-post-excerpt">
                 	<?php the_excerpt(); ?>
                 </div>
            </div>
         </div>
      </div>
   </div>
</article>
<?php 
	endwhile; 
	endif;	?>
</section>
<?php 

wp_reset_postdata(); 

return ob_get_clean();
}

?>