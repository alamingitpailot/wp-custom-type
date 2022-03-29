<?php 


/**
 *  
 * Post Type Register
 * Author bio field post type register
 * 
 */ 
add_action( 'init', "Ccpt_create_post_type" );
function Ccpt_create_post_type(){ 

	register_post_type("authors",array(
		"labels" => array(
			"name" => "Authors",
			"all_items" => "All Authors",
			"add_new" => "Add New",
			"add_new_item" => "Author Full Name",
            "featured_image" => "Author Image",
            "set_featured_image" => "Set author image",
            "remove_featured_image" => "Remove author image" ,
            "use_featured_image" => "Use as cover image",
		),
		"public" => true,
        'has_archive' => true,
		"supports" => array("title","thumbnail"),
		"menu_icon" => "dashicons-buddicons-buddypress-logo"
	));
}


/**
 *  
 * Title Placholder change
 * Author Full Name Change
 * 
 */
add_filter( 'enter_title_here', 'Ccpt_change_title_text' );
function Ccpt_change_title_text( $title ){
     $screen = get_current_screen();
     if  ( 'authors' == $screen->post_type ) {
          $title = "Enter's Authors full Name";
     }
     return $title;
}


/**
 *  
 * Post Type Metabox
 * Author bio field metabox
 * 
 */
add_action( 'admin_menu', 'Ccpt_add_metabox' );
function Ccpt_add_metabox() {

    add_meta_box(
        'Ccpt_biofield_metabox', 
        'Author Info',
        'Ccpt_metabox_callback', 
        'authors',
        'normal',
        'default'
    );
}


/**
 *  
 * Post Type Metabox
 * Author bio field Callback Function
 * 
 */
function Ccpt_metabox_callback( $post ) {

    $first_name = get_post_meta( $post->ID, 'first_name', true );
    $last_name = get_post_meta( $post->ID, 'last_name', true );
    $biography = get_post_meta( $post->ID, 'biography', true );
    $facebook_url = get_post_meta( $post->ID, 'facebook_url', true );
    $linkedin_url = get_post_meta( $post->ID, 'linkedin_url', true );
    $instagram_url = get_post_meta( $post->ID, 'instagram_url', true );
    $wp_user_name = get_post_meta( $post->ID, 'wp_user_name', true );

    // nonce, actually I think it is not necessary here
    wp_nonce_field( 'somerandomstr', '_mishanonce' );

    ?><table class="form-table">
        <tbody>
            <tr>
                <th><label for="first_name">First Name</label></th>
                <td><input type="text" id="first_name" name="first_name" value="<?php esc_attr_e( $first_name ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="last_name">Last Name</label></th>
                <td><input type="text" id="last_name" name="last_name" value="<?php esc_attr_e( $last_name ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="biography">Biography</label></th>
                <td><textarea id="biography" name="biography" rows="4" cols="50"><?php  esc_html_e( $biography ); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="facebook_url">Facebook Url</label></th>
                <td><input type="url" id="facebook_url" name="facebook_url" value="<?php esc_attr_e( $facebook_url ); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="linkedin_url">Linkedin Url</label></th>
                <td><input type="url" id="linkedin_url" name="linkedin_url" value="<?php esc_attr_e( $linkedin_url );?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="instagram_url">Instagram URL</label></th>
                <td><input type="url" id="instagram_url" name="instagram_url" value="<?php esc_attr_e( $instagram_url ); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="wp_user_name">Wordpress User Name</label></th>

                <td>
                    <select id="wp_user_name" name="wp_user_name">
                        <option value=" ">--Select--</option>
                         <?php 
                            $users = get_users();
                            foreach($users as $user): ?> 
                            <option value="<?php echo $user->ID; ?>" class="" <?php selected( $wp_user_name, $user->ID ) ?>><?php echo $user->display_name; ?></option>
                            <?php endforeach ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table><?php
   
}


/**
 *  
 * Post Type Metabox
 * Author bio field Save Data
 * 
 */

add_action( 'save_post', 'Ccpt_save_meta', 10, 2 );
function Ccpt_save_meta( $post_id, $post ) {

    // nonce check
    if ( ! isset( $_POST[ '_mishanonce' ] ) || ! wp_verify_nonce( $_POST[ '_mishanonce' ], 'somerandomstr' ) ) {
        return $post_id;
    }

    // check current use permissions
    $post_type = get_post_type_object( $post->post_type );

    if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
        return $post_id;
    }

    // Do not save the data if autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // define your own post type here
    if( $post->post_type != 'authors' ) {
        return $post_id;
    }

    if( isset( $_POST[ 'first_name' ] ) ) {
        update_post_meta( $post_id, 'first_name', sanitize_text_field( $_POST[ 'first_name' ] ) );
    } else {
        delete_post_meta( $post_id, 'first_name' );            
    }
    if( isset( $_POST[ 'last_name' ] ) ) {
        update_post_meta( $post_id, 'last_name', sanitize_text_field( $_POST[ 'last_name' ] ) );
    } else {
        delete_post_meta( $post_id, 'last_name' );            
    }
    if( isset( $_POST[ 'biography' ] ) ) {
        update_post_meta( $post_id, 'biography', sanitize_text_field( $_POST[ 'biography' ] ) );
    } else {
        delete_post_meta( $post_id, 'biography' );            
    }
    if( isset( $_POST[ 'facebook_url' ] ) ) {
        update_post_meta( $post_id, 'facebook_url', sanitize_text_field( $_POST[ 'facebook_url' ] ) );
    } else {
        delete_post_meta( $post_id, 'facebook_url' );            
    }
    if( isset( $_POST[ 'linkedin_url' ] ) ) {
        update_post_meta( $post_id, 'linkedin_url', sanitize_text_field( $_POST[ 'linkedin_url' ] ) );
    } else {
        delete_post_meta( $post_id, 'linkedin_url' );            
    }
    if( isset( $_POST[ 'instagram_url' ] ) ) {
        update_post_meta( $post_id, 'instagram_url', sanitize_text_field( $_POST[ 'instagram_url' ] ) );
    } else {
        delete_post_meta( $post_id, 'instagram_url' );            
    }
    if( isset( $_POST[ 'wp_user_name' ] ) ) {
        update_post_meta( $post_id, 'wp_user_name', sanitize_text_field( $_POST[ 'wp_user_name' ] ) );
    } else {
        delete_post_meta( $post_id, 'wp_user_name' );            
    }
    return $post_id;
}


/* 
  *
  *Custom MEta box Image Gallery
  *
  *
  */
function Ccpt_image_gallery_field( $name, $value = '' ) {

    $html = '<div><ul class="misha_gallery_mtb">';
    /* array with image IDs for hidden field */
    $hidden = array();

    if( $images = get_posts( array(
        'post_type' => 'attachment',
        'orderby' => 'post__in', /* we have to save the order */
        'order' => 'ASC',
        'post__in' => explode(',',$value), /* $value is the image IDs comma separated */
        'numberposts' => -1,
        'post_mime_type' => 'image'
    ) ) ) {

        foreach( $images as $image ) {
            $hidden[] = $image->ID;
            $image_src = wp_get_attachment_image_src( $image->ID, array( 80, 80 ) );
            $html .= '<li data-id="' . $image->ID .  '"><img src="' . $image_src[0] . '"></span><a href="#" class="misha_gallery_remove">&times;</a></li>';
        }

    }

    $html .= '</ul><div style="clear:both"></div></div>';
    $html .= '<input type="hidden" name="'.$name.'" value="' . join(',',$hidden) . '" /><a href="#" class="button misha_upload_gallery_button">Upload Image</a>';

    return $html;
}


/*
 *
 * Add a meta box
 *
 */
add_action( 'admin_menu', 'Ccpt_meta_box_add' );
function Ccpt_meta_box_add() {
    add_meta_box(
        'Ccpt_image_gallery', 
        'Image Gallery', 
        'Ccpt_image_gallery_print_box', 
        'authors',
        'normal', 
        'high' 
    ); 
}
 
/*
 * Image Gallery Meta bos callback function
 * Meta Box HTML
 *
 *
*/
function Ccpt_image_gallery_print_box( $post ) {
    $meta_key = 'custom_gallery';
    echo Ccpt_image_gallery_field( $meta_key, get_post_meta($post->ID, $meta_key, true) );
}
 
/*
 * Meta box Image Gallery Save Meta box
 * Save Meta Box data
 */
add_action('save_post', 'Ccpt_image_gallery_save');
function Ccpt_image_gallery_save( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
 
    $meta_key = 'custom_gallery';
 
    update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );
 
    return $post_id;
}

/*
 * search shortcode
 * 
 */
add_shortcode('search_filter', 'Ccpt_search_filter');
function Ccpt_search_filter(){
    ob_start();
    ?>
    <form action="" method="post" id="search-filter-form">
        <input type="text" name="search" placeholder="author name">
        <div class="search_button_area">
            <input type="hidden" name="sraction" value="search_filter_form">
            <?php wp_nonce_field( 'Ccpt_nonce' ); ?>
            <input type="submit" name="" id="search" value="search">

        </div>
    </form>
    <?php
    $output = ob_get_clean();
    return $output;
}


/*
 *Author single page Template load
 *
 *
*/

define( 'MY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_TEMPLATE_DIR', MY_PLUGIN_DIR . '/templates/' );

add_filter( 'template_include', 'Ccpt_include_single_template', 99 );
function Ccpt_include_single_template( $template ) {

    $new_template = '';

    $provided_template_array = explode( '/', $template );

    // This will give us archive.php
    $new_template = end( $provided_template_array );

    // Define single and archive template for custom post type 'portfolio'
    if( is_singular('authors') ) {
        $new_template = 'single-authors.php';
    }

    $plugin_template = MY_PLUGIN_TEMPLATE_DIR . $new_template;

    if( file_exists( $plugin_template ) ) {
    return $plugin_template;
    }

    return $template;
}    

/*
 *Archive Template Load
 *
 *
*/
add_filter('archive_template', 'Ccpt_authors_archive_page_template');
function Ccpt_authors_archive_page_template($archive_template){
    
    if (is_post_type_archive('authors')){
          $archive_template = dirname( __FILE__ ).'/templates/authors.php';
    }
    return $archive_template;
}


/*
 *
 *Archive Register Template
 *
*/
add_filter('archive_template', 'Ccpt_authors_Register');
function Ccpt_authors_Register($archive_template){
    
    if (is_post_type_archive('register')){
          $archive_template = dirname( __FILE__ ).'/templates/register.php';
    }
    return $archive_template;
}


/*
 *
 *Edit permalink
 *New Permalink set
 *
*/
add_action( 'save_post', 'Ccpt_slug_save_post_callback', 10, 3 );
function Ccpt_slug_save_post_callback( $post_ID, $post, $update ) {
    // allow 'publish', 'draft', 'future'
    if ($post->post_type != 'authors' || $post->post_status == 'auto-draft')
        return;

    // only change slug when the post is created (both dates are equal)
    if ($post->post_date_gmt != $post->post_modified_gmt)
        return;

    // use title, since $post->post_name might have unique numbers added
    $first_name = sanitize_title( $post->first_name, $post_ID );
    $last_name = sanitize_title( $post->last_name, $post_ID );
    $first_name = str_replace("-", "_", $first_name);
    $last_name = str_replace("-", "_", $last_name);
    
    if (empty( $last_name ) || strpos( $first_name, $last_name ) !== false)
        return; // No last name or already in slug
    
    $first_name .= '-' . $last_name;

    // hook remove this function infinite loop
    remove_action( 'save_post', 'slug_save_post_callback', 10, 3 );
    // update the post slug
    wp_update_post( array(
        'ID' => $post_ID,
        'post_name' => $first_name
    ));
    // re-hook function
    add_action( 'save_post', 'slug_save_post_callback', 10, 3 );
}
