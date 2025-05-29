<?php

class ShoppingCart
{
  public function __construct()
  {
    if (!isset($_SESSION["cart"])) {
      $_SESSION["cart"] = [];
    }
  }

  public function add($product, $quantity = 1)
  {
    $id = $product["id"];
    if (isset($_SESSION["cart"][$id])) {
      $_SESSION["cart"][$id]['quantity'] += $quantity;
    } else {
      $_SESSION["cart"][$id] = [
        'id' => $id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
      ];
    }
  }

  public function remove($product)
  {
    $id = $product['id'];
    unset($_SESSION['cart'][$id]);
  }

  public function update($product, $quantity)
  {
    $id = $product['id'];
    if (isset($_SESSION['cart'][$id])) {
      if ($quantity <= 0) {
        $this->remove($id);
      } else {
        $_SESSION['cart'][$id]['quantity'] = $quantity;
      }
    }
  }

  public function clear()
  {
    $_SESSION['cart'] = [];
  }

  public function getItems()
  {
    return $_SESSION['cart'];
  }

  public function getTotal()
  {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
    return $total;
  }
}
