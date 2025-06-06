<?php
session_start();

include_once("productStorage.php");
include_once('userStorage.php');
include_once("auth.php");


$auth = new Auth(new UserStorage());
if (!$auth->is_authenticated()) {
  header("Location: login.php");
  exit();
}

$ps = new ProductStorage();
$us = new UserStorage();

$cart = $_SESSION['cart'] ?? [];
$user = $_SESSION['user'] ?? null;

if (empty($cart)) {
  $_SESSION['message'] = "Your cart is empty.";
  header("Location: cart.php");
  exit();
}

if (!$user || !isset($user['id'])) {
  $_SESSION['message'] = "User not found.";
  header("Location: cart.php");
  exit();
}

$errors = [];

foreach ($cart as $productId => $quantity) {
  $product = $ps->findById($productId);
  if (!$product) {
    $errors[] = "Product not found.";
    continue;
  }
  if ($product['stock'] < $quantity) {
    $errors[] = "Not enough stock for item: {$product['name']}.";
  }
}

if (!empty($errors)) {
  $_SESSION['message'] = $errors;
  header("Location: cart.php");
  exit();
}

$userId = $user['id'];
$userData = $us->findById($userId);

foreach ($cart as $productId => $quantity) {
  $product = $ps->findById($productId);
  $product['stock'] -= $quantity;
  $ps->update($productId, $product);

  $userData['purchases'][] = [
    'product' => $product['name'],
    'quantity' => $quantity,
    'date' => date("Y-m-d")
  ];
}

$us->update($userId, $userData);


$_SESSION['cart'] = [];
$_SESSION['user'] = $userData;
$_SESSION['message'] = "Purchase successful";

header("Location: index.php");
exit();
