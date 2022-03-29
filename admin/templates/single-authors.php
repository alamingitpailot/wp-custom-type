<?php 
get_header();

$first_name = get_post_meta( get_the_id(), 'first_name', true );
$last_name = get_post_meta( get_the_id(), 'last_name', true );
$biography = get_post_meta( get_the_id(), 'biography', true );
$facebook_url = get_post_meta( get_the_id(), 'facebook_url', true );
$linkedin_url = get_post_meta( get_the_id(), 'linkedin_url', true );
$instagram_url = get_post_meta( get_the_id(), 'instagram_url', true );
$post_meta_data = get_post_meta( get_the_id(), 'custom_gallery', true );
?>
<div class="single_author_value">
    <div class="single_author_image">
        <?php the_post_thumbnail(); ?>
    </div>
    <div class="single_author_title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </div>
    <div class="author_details">
        <?php if( !empty( $first_name )){?>
        <p>Author First Name : <?php echo $first_name; ?></p>
        <?php } ?>
        <?php if( !empty( $last_name )){?>
        <p>Author Last Name : <?php echo $last_name; ?></p>
        <?php } ?>
        <?php if( !empty( $biography )){?>
            <p>Author Biography : <?php echo $biography; ?></p>
        <?php } ?>
    </div>
    <div class="author_social_link">
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
    <?php 

    // Show the Image Gallery 
    $id = get_the_ID();
    $gallery_img = get_post_meta($id, 'custom_gallery', true);  
    $gallery_img = explode(',', $gallery_img);
    if(!empty($gallery_img)) {
        ?>
        <h4><?php _e( 'Gallery', 'Ccpt' ); ?></h4>
        <div class="author_image_gallery">
            <ul>
                <?php  foreach ($gallery_img as $attachment_id) { ?>
                 <li><img src="<?php echo wp_get_attachment_url( $attachment_id );?>"></li>            
                <?php } ?>
            </ul>
        </div>   
        <?php
    }
     ?>
</div> 
<?php 
    $user_id = get_post_meta( get_the_id(), 'wp_user_name', true );
    
    if( !empty( $user_id )){
        echo "<h3>Posts</h3>";
        $args = array(
            'numberposts'   => -1,
            'post_type'     => 'post',
            'author'        => $user_id
        );
        $the_query = new WP_Query($args);
        if ( $the_query->have_posts() ) : ?>
            <?php while ( $the_query->have_posts() ) : $the_query->the_post();?>
            <div class="author_single_card">
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <div class="post_content">
                    <p><?php the_content(); ?></p>
                </div>
            </div>
    <?php endwhile;endif;wp_reset_postdata(); } ?>
<?php 
get_footer();

   

