<?php
/**
 * Plugin Name: Remove Free Shipping Price
 * Description: Remove price from shipping when it's free
 * Version: 1.0.1
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
          console.log("Shipping price not found.");
        }
      });
      ';
}

function get_free_shipping_min_value($zone_name = 'Colombia')
{
  $result = 75000;
  $zone = null;

  $zones = WC_Shipping_Zones::get_zones();
  foreach ($zones as $z) {
    if ($z['zone_name'] == $zone_name) {
      $zone = $z;
      break;
    }
  }

  if ($zone) {
    $shipping_methods_nl = $zone['shipping_methods'];
    $free_shipping_method = false;
    foreach ($shipping_methods_nl as $method) {
      if ($method->id == 'free_shipping') {
        $free_shipping_method = $method;
        break;
      }
    }

    if ($free_shipping_method) {
      $result = $free_shipping_method->min_amount;
    }
  }

  return $result;
}

function remove_price()
{
  global $woocommerce;
  $order_total = $woocommerce->cart->get_subtotal();
  $free_shipping = get_free_shipping_min_value("Colombia");
  if ($order_total >= $free_shipping) {
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