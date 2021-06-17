<?php 
/**
 * To overwrite this template create a file name before-add-btn.php
 * and put it in your active-theme-folder/woocommerce folder
 */

$sit_admin_btn_html = get_option( SIT_BEFORE_ADDED_BTN_HTML, false );

if( $sit_admin_btn_html == false || ( is_string($sit_admin_btn_html) && trim($sit_admin_btn_html) == '' ) ){
    // render default template
    echo '<div class="wishlist-btn-inner"><img src="'.SIT_PLUGIN_URL.'/dist/icons/heart-before.svg" alt="remove icon" /> Add to wishlist</div>';
}else{
    // if button html set
    echo wp_filter_post_kses($sit_admin_btn_html);
}