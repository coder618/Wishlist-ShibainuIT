<?php
$modal_html = '';

add_action( 'wp_footer', 
    function(){
        ob_start();
            sit_wishlist_template("sit-my-wishlist-modal.php");                
            $modal_html = ob_get_contents();
        ob_end_clean();
        echo $modal_html;
    }
);
