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
    $free = "Gratis!";
    return 'document.addEventListener("DOMContentLoaded", () => {
        let cartTotals = document.getElementsByClassName("cart_totals ").item(0);
        let shippingTotals = cartTotals.getElementsByClassName("shipping ").item(0);
        let calculateShippingText = shippingTotals.children[1].children[0];
        calculateShippingText.innerHTML = "' . $free . '";
    });';
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
function filter_woocommerce_update_cart_action_cart_updated($cart_updated)
{
    remove_price();

    return $cart_updated;
}

add_filter('woocommerce_update_cart_action_cart_updated', 'filter_woocommerce_update_cart_action_cart_updated', 10, 1);
add_filter('woocommerce_get_item_data', 'get_cart_item_data', 10, 2);
?>