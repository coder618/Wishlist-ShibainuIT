<?php 
// main ajax functions

add_action( 'wp_ajax_sit_update_wishlist', 'sit_update_wishlist' );

function sit_update_wishlist(){
    if(  
        isset( $_POST['sit_post_id'] )  && !empty( $_POST['sit_post_id'] ) &&
        isset( $_POST['sit_action'] )   && !empty( $_POST['sit_action'] ) &&
        isset( $_POST['sit_nonce'] )    && !empty( $_POST['sit_nonce'] )     
    ){  
        $sit_nonce = sanitize_key( $_POST['sit_nonce'] );

        // checking nonce
        if( wp_verify_nonce( $sit_nonce, 'sit-wishlist' ) === false ){
            echo json_encode([
                'status'    => false,
                'message'   => "Nonce verify failed",
                'nonce'     => $sit_nonce
            ]);
            die();
        }

        $key = SIT_USER_META_KEY;

        $wishlist_action    = sanitize_text_field( trim($_POST['sit_action']) );
        $c_user_id          = get_current_user_id();
        $c_post_id          = intval($_POST['sit_post_id']);
        $item_in_wishlist   = false;
        $btn_inner_html     = '';

        

        
        $wishlist_array = get_user_meta( $c_user_id, $key, true );
        
        if( !$wishlist_array ){
            $wishlist_array = [];
        }

        // check if item already in wishlist
        if( in_array( $c_post_id,  $wishlist_array)){
            $item_in_wishlist = true;
        }


        if( $wishlist_action == 'add' ){

            if( $item_in_wishlist  ){
                echo json_encode([
                    'status' => false,
                    'message' => "Item already have in your wishlist",                    
                ]);

            }else{
                $wishlist_array[] = $c_post_id;
                update_user_meta($c_user_id, $key, $wishlist_array);
                
                // get the button html 
                ob_start();
                    sit_wishlist_template("after-add-btn.php");                
                    $btn_inner_html = ob_get_contents();
                ob_end_clean();

                echo json_encode([
                    'status' => true,
                    'message' => "Item added to your wishlist",
                    'btn_inner_html' => $btn_inner_html
                ]);
            }

        }elseif($wishlist_action == 'remove' ){

            if( $item_in_wishlist ){

                // unset($wishlist_array[$c_post_id]);
                unset($wishlist_array[array_search($c_post_id,$wishlist_array)]);

                update_user_meta($c_user_id, $key, $wishlist_array);
                
                // get the button html 
                ob_start();
                    sit_wishlist_template("before-add-btn.php");                
                    $btn_inner_html = ob_get_contents();
                ob_end_clean();
                echo json_encode([
                    'status' => true,
                    'message' => "Item removed from your wishlist",
                    'btn_inner_html' => $btn_inner_html
                ]);

            }else{

                echo json_encode([
                    'status' => false,
                    'message' => "Item not found in your wishlist or it already removed"
                ]);
                
            }

        }else{
            echo json_encode([
                'status' => false,
                'message' => "Please check your request. no action matched"
            ]);
        }

    }else{
        echo json_encode([
            'status' => false,
            'message' => "Invalid Request"
        ]);
    }

    die();

}



add_action( 'wp_ajax_sit_update_wishlist_settings', 'sit_update_wishlist_settings' );

function sit_update_wishlist_settings(){

    // check is login
    if(!is_user_logged_in()){
        echo json_encode([
            'status'    => false,
            'message'   => "You need to login first!",
        ]);
        die();
    }
    
    // check admin 
    $current_user = get_user_by('id', get_current_user_id(  ));
    $roles_arr = $current_user->roles;
    
    if( !in_array('administrator', $roles_arr) ){
        echo json_encode([
            'status'    => false,
            'message'   => "You need to have admin role for this.",
        ]);
        die();
    }




    if(  
        isset( $_POST['sit_default_btn_visibility'] )  && !empty( $_POST['sit_default_btn_visibility'] ) &&        
        isset( $_POST['sit_nonce'] )    && !empty( $_POST['sit_nonce'] )     
    ){  


        $sit_nonce = sanitize_key( $_POST['sit_nonce'] );
        $sit_default_btn_visibility = sanitize_text_field(  $_POST['sit_default_btn_visibility'] );
        $before_html = '';
        $after_html = '';
        
        if(isset( $_POST['before_html'] )   && !empty( $_POST['before_html'] )){
            $before_html = wp_filter_post_kses(  $_POST['before_html'] );
        }
        if( isset( $_POST['after_html'] )   && !empty( $_POST['after_html'] ) ){
            $after_html = wp_filter_post_kses(  $_POST['after_html'] );
        }


        // checking nonce
        if( wp_verify_nonce( $sit_nonce, 'sit-wishlist' ) === false ){
            echo json_encode([
                'status'    => false,
                'message'   => "Nonce verify failed",
                'nonce'     => $sit_nonce
            ]);
            die();
        }

        // update the option
        update_option( SIT_BEFORE_ADDED_BTN_HTML, $before_html );
        update_option( SIT_AFTER_ADDED_BTN_HTML, $after_html );
        
        if($sit_default_btn_visibility){
            update_option( SIT_DEFAULT_WISHLIST_BTN_VISIBILITY, $sit_default_btn_visibility );
        }

        echo json_encode([
            'status' => true,
            'message' => "Settings saved successfully",
            'before_html' => $before_html,
            'after_html' => $after_html,
            'sit_default_btn_visibility' => $sit_default_btn_visibility,
            // 'role'      => $current_user->roles,
        ]);
        
    }else{
        echo json_encode([
            'status' => false,
            'message' => "Invalid Request",
            // 'role'      => $current_user->roles,
        ]);
    }

    die();

}
