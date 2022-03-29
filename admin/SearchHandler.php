<?php 


/**
 * The Search Handler
 */

class Ccpt_SearchHandler{
	
	/**
     * Initialize the class
     */ 

	function __construct(){
		// Ajax Add data options table 
		add_action( 'wp_ajax_search_filter_form', [ $this, 'search_filter' ] );
	}

	// Setting add form action
    function search_filter(){

      if ( isset( $_POST['sraction'] ) && $_POST['sraction'] == "search_filter_form" ) {

      	$value =  isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
        
        $args = array(
            'numberposts'   => 1,
            'post_type'     => 'authors',
            'title'         => $value,
        );

        $the_query = new WP_Query($args);
        if ( $the_query->have_posts() ) : ?>
        <?php while ( $the_query->have_posts() ) : $the_query->the_post();
        $biography = get_post_meta( get_the_id(), 'biography', true );
        $facebook_url = get_post_meta( get_the_id(), 'facebook_url', true );
        $linkedin_url = get_post_meta( get_the_id(), 'linkedin_url', true );
        $instagram_url = get_post_meta( get_the_id(), 'instagram_url', true );
        ?>
        <div class="author_single_card">
            <div class="search_author_image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
            </div>
            
            <h4> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <p><?php echo $biography; ?></p>
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
        <?php endwhile; ?>
     
        <?php wp_reset_postdata(); ?>
     
        <?php else : ?>
            <p><?php _e( 'Sorry, no result' ); ?></p>
        <?php endif; ?>

    <?php        

        }
        die();
    }
}    
new Ccpt_SearchHandler();