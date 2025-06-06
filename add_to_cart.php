<?php
session_start();
include_once("productStorage.php");

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $ps = new ProductStorage();
  $product = $ps->findById($id);


  if ($product) {
    $currentQuantity = $_SESSION['cart'][$id] ?? 0;

    if ($currentQuantity < $product['stock']) {
      $_SESSION['cart'][$id] = $currentQuantity + 1;
      $_SESSION['message'] = 'Item added to cart';
    } else {
      $_SESSION['message'] = 'We dont have stock for this item';
    }
  } else {
    $_SESSION['message'] = 'Item not found';
  }
}

header("Location: index.php");
exit();
