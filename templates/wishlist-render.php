<?php 
/**
 * To overwrite this template create a file name wishlist-render.php
 * and put it in your active-theme-folder/woocommerce folder
 */

$wishlist_array = $sit_wishlist_ids;

if( !$wishlist_array ){
    $wishlist_array = [];
}

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'post__in' => $wishlist_array
);
$loop = new WP_Query( $args );

echo '<div class="sit-wishlist-wrapper"> <h1>Wishlist Items</h1>';

if($wishlist_array && $loop->have_posts() ):
?>
    <table style="width:100%" class="sit-wishlist-table">

        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php  while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
            <tr>
                <td><?php echo get_the_title( ) ?></td>
                <td><?php echo  $product->get_price_html() ?></td>
                <td>
                    <div class="action-btns">
                        <a class="woocommerce-Button button sit-btn sit-table-view-btn" href="<?php echo get_permalink() ?>">View Item</a>
                        <?php 
                            echo  "<button data-nonce='".wp_create_nonce('sit-wishlist')."' type='button' data-admin-url='".admin_url( 'admin-ajax.php' )."' class='woocommerce-Button button sit-btn sit-table-remove sit-wishlist-btn sit-dashboard-btn' data-post-id='".get_the_ID(  )."' data-action='remove'>REMOVE ITEM</button>";
                        ?>
                    </div>
                
                </td>
            </tr>
    
        <?php endwhile; wp_reset_postdata(); ?>
        </tbody>
    </table>

<?php 
else:
    echo "<div class='alert alert-error'>You have no wishlist item.</div>";
endif;
echo '</div>';
