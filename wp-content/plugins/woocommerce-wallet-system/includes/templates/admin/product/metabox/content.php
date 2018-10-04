<?php

if( ! defined ( 'ABSPATH' ) )
  exit;


global $post;

?>
<form action = "" method = "POST">
    <?php wp_nonce_field( 'wc_wallet_cashback_product', 'wc_wallet_cashback_product_nonce' ); ?>
<div class="wc-wallet-product-metabox-wrapper">
  <p>
    <label for="product-meta-quantity">Quantity</label>
  </p>
  <p>
    <input type="text" name="cashback_min_quantity_restriction" id="product-meta-quantity" value="<?php echo get_post_meta( $post->ID, '_cashback_min_quantity_restriction', true ); ?>" />
  </p>
  <p>
    <label for="product-meta-cashback-type">Type</label>
  </p>
  <p>
    <select id="product-meta-cashback-type" name="cashback_type_restriction">
      <option value="fixed" <?php if( get_post_meta( $post->ID, '_cashback_type_restriction', true ) == 'fixed' ) echo 'selected="selected"'; ?> >Fixed</option>
      <option value="percentage" <?php if( get_post_meta( $post->ID, '_cashback_type_restriction', true ) == 'percentage' ) echo 'selected="selected"'; ?> >Percentage</option>
    </select>
  </p>
  <p>
    <label id="product-meta-cashback-amount">Amount</label>
  </p>
  <p>
    <input type="product-meta-cashback-amount" name="cashback_amount_awarded" value="<?php echo get_post_meta( $post->ID, '_cashback_amount_awarded', true ); ?>" />
  </p>
</div>
