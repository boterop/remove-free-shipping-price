<?php
/**
 * Plugin Name: Remove Free Shipping Price
 * Description: Remove price from shipping when it's free
 * Version: 1.0.0
 * Author: Santiago Botero
 * Author URI: https://www.boterop.io
 * Text Domain: remove-free-shipping-price
 * License: The MIT License
 * License URI: https://opensource.org/license/mit
 */

function change_shipping_script()
{
    $free = "<strong>Gratis!</strong>";
    return 'document.addEventListener("DOMContentLoaded", () => {
        try {
          let cartTotals = document.getElementsByClassName("cart_totals ").item(0);
          let shippingTotals = cartTotals.getElementsByClassName("shipping ").item(0);
          let calculateShippingText = shippingTotals.children[1].children[0];
          calculateShippingText.innerHTML = "' . $free . '";
        } catch (error) {
          console.log("Error price: ");
        }
      });
      ';
}

function remove_price()
{
    global $woocommerce;
    $order_total = $woocommerce->cart->get_subtotal();
    if ($order_total >= 75000) {
        echo "<script>console.log('Removing shipping price...' );</script>";
        echo "<script>" . change_shipping_script() . "</script>";
    }
}

function get_cart_item_data($data, $cartItem)
{
    remove_price();

    return $data;
}

add_filter('woocommerce_get_item_data', 'get_cart_item_data', 10, 2);
?>