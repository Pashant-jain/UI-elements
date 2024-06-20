<?php


/* This is the string which will gather all the information.*/
$lcp_display_output = '';

$lcp_display_output .= $this->get_category_link('strong');

$lcp_display_output .= $this->get_category_description();

$lcp_display_output .= $this->get_conditional_title();

$lcp_display_output .= $this->open_outer_tag('ul', 'lcp_catlist');


global $post;
while ( have_posts() ):
  the_post();

  if (!$this->check_show_protected($post)) continue;

  $lcp_display_output .= $this->open_inner_tag($post, 'li');

  $lcp_display_output .= $this->get_post_title($post);
  $lcp_display_output .= $this->get_comments($post);
  $lcp_display_output .= $this->get_date($post);
  $lcp_display_output .= $this->get_modified_date($post);
  $lcp_display_output .= $this->get_author($post);
  $lcp_display_output .= $this->get_display_id($post);
  $lcp_display_output .= $this->get_custom_fields($post);
  $lcp_display_output .= $this->get_thumbnail($post);
  $lcp_display_output .= $this->get_content($post, 'p', 'lcp_content');

  $lcp_display_output .= $this->get_excerpt($post, 'div', 'lcp_excerpt');

  $lcp_display_output .= $this->get_posts_morelink($post);

  $lcp_display_output .= $this->close_inner_tag();
endwhile;

$lcp_display_output .= $this->get_no_posts_text();
$lcp_display_output .= $this->close_outer_tag();
$lcp_display_output .= $this->get_morelink();
$lcp_display_output .= $this->get_category_count();
$lcp_display_output .= $this->get_pagination();

$this->lcp_output = $lcp_display_output;
