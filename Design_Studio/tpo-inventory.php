<?php
# Load WordPress
require_once('wp-load.php');

header('Content-Type: application/json');

# Get 8 recent products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'post_status' => 'publish',
);

$loop = new WP_Query( $args );
$products = array();

while ( $loop->have_posts() ) : $loop->the_post();
    global $product;
    
    $products[] = array(
        'id' => $product->get_id(),
        'title' => $product->get_name(),
        'price' => $product->get_price_html(),
        'image' => get_the_post_thumbnail_url($product->get_id(), 'medium'),
        'link' => get_permalink(),
        'add_to_cart' => '?add-to-cart=' . $product->get_id(),
    );
endwhile;

wp_reset_query();

echo json_encode($products);
?>