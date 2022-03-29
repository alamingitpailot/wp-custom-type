<?php 

get_header();

?>

<h1><?php _e( 'Authors List', 'Ccpt' ); ?></h1>

<?php

/*
 *
 * SHORTCODE ASSIGN
 *
 */
echo do_shortcode('[search_filter]');

$image = new WP_Query(array(
	"post_type" => "authors",
	"posts_per_page" => -1,
	"order" => "DESC"
));
?>
<div class="author_single_card_main_section">

<div class="author_single_card_section">
	<?php while($image->have_posts()):$image->the_post(); 
		$biography = get_post_meta( get_the_id(), 'biography', true );
		$facebook_url = get_post_meta( get_the_id(), 'facebook_url', true );
	    $linkedin_url = get_post_meta( get_the_id(), 'linkedin_url', true );
	    $instagram_url = get_post_meta( get_the_id(), 'instagram_url', true );
	?>
	<div class="author_single_card">	
		<div class="author_image">
			<a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail(); ?></a>
		</div>
		<div class="author_name">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
		<?php if( !empty( $biography )){?>
			<p><?php echo $biography; ?></p>
	    <?php } ?>
		<?php if( !empty( $facebook_url )){?>
            <a href="<?php echo $facebook_url; ?>">Facebook</a>
        <?php } ?>
        <?php if( !empty( $linkedin_url )){?>
            <a href="<?php echo $linkedin_url; ?>"> Linkedin</a>
        <?php } ?>
        <?php if( !empty( $instagram_url )){?>
            <a href="<?php echo $instagram_url; ?>"> Instagram</a>
        <?php } ?>
	</div>
	<?php endwhile; wp_reset_postdata(); ?>                     
</div>
</div>
<?php

get_footer();
 