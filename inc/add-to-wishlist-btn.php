<?php 
/**
 * Button HTML 
 */

// check if user want to visible the default button
if( get_option( SIT_DEFAULT_WISHLIST_BTN_VISIBILITY , 'visible' ) == 'visible' ){
    add_action( 'woocommerce_after_add_to_cart_button', 'sit_after_add_to_cart_btn' );
}

// add shortcode support 
add_shortcode( 'SIT-WISHLIST-BUTTON', 'sit_after_add_to_cart_btn' );


function sit_after_add_to_cart_btn(){
    // check is user login or not
    if(is_user_logged_in()){

        $c_user_id      = get_current_user_id();
        $c_post_id      = get_the_ID(  );
        $btn_html       = '';

        $wishlist_array = get_user_meta( $c_user_id, SIT_USER_META_KEY , true )  ;

        if( !$wishlist_array ){
            $wishlist_array = [];
        }

        $admin_url = admin_url( 'admin-ajax.php' );
        echo "<div class='sit-wishlist-btn-wrapper' >";
            if(in_array( $c_post_id,  $wishlist_array)): ?>
                <button class="sit-wishlist-btn" data-nonce="<?php echo esc_attr(wp_create_nonce('sit-wishlist')) ?>" data-post-id="<?php echo esc_attr($c_post_id) ?>" data-action="remove" data-admin-url="<?php echo esc_url($admin_url) ?>" >
                    <?php sit_wishlist_template('after-add-btn.php') ?>
                </button>
            <?php else: ?>
                <button class="sit-wishlist-btn" data-nonce="<?php echo esc_attr(wp_create_nonce('sit-wishlist')) ?>" data-post-id="<?php echo esc_attr($c_post_id) ?>" data-action="add" data-admin-url="<?php echo esc_url($admin_url) ?>" >
                    <?php sit_wishlist_template('before-add-btn.php'); ?>
                </button>
            <?php endif; 
        echo "</div>";

    }
    return "";
}
