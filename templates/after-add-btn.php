<?php

/**
 * To overwrite this template create a file name after-add-btn.php
 * and put it in your active-theme-folder/woocommerce folder
 */
$sit_admin_btn_html = get_option( SIT_AFTER_ADDED_BTN_HTML, false );
$sit_after_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.435c-1.989-5.399-12-4.597-12 3.568 0 4.068 3.06 9.481 12 14.997 8.94-5.516 12-10.929 12-14.997 0-8.118-10-8.999-12-3.568z"/></svg>';

if( $sit_admin_btn_html == false || ( is_string($sit_admin_btn_html) && trim($sit_admin_btn_html) == '' ) ){
    // render default template    
    echo stripslashes('<div class="sit-wishlist-btn-inner">'.$sit_after_icon.'Remove from wishlist</div>');
}else{
    // if button html set
    echo stripslashes_deep( $sit_admin_btn_html  );
}