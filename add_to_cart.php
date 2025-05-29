<?php
session_start();

include_once("productStorage.php");
include_once("shoppingCart.php");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? null;

if (!$id) {
  http_response_code(400);
  echo json_encode(["error" => "Product ID missing"]);
  exit;
}

$ps = new ProductStorage();
$product = $ps->findById($id);

if (!$product) {
  http_response_code(404);
  echo json_encode(["error" => "Product not found"]);
  exit;
}

$cart = new ShoppingCart();
$cart->add($product);

echo json_encode(["success" => true]);

if (!$id) {
  http_response_code(400);
  echo json_encode(["error" => "Product ID missing"]);
  exit;
}

$ps = new ProductStorage();
$product = $ps->findById($id);

if (!$product) {
  http_response_code(404);
  echo json_encode(["error" => "Product not found"]);
  exit;
}

$cart = new ShoppingCart();
$cart->add($product);

echo json_encode(["success" => true]);
