<?php
session_start();

include_once("productStorage.php");
include_once("userStorage.php");
include_once("auth.php");
include_once("shoppingCart.php");

$auth = new Auth(new UserStorage());
if (!$auth->is_authenticated()) {
  header("Location: login.php");
  exit();
}

$user_storage = new UserStorage();
$cart = new ShoppingCart();

print_r($_SESSION["cart"]);

require 'header.php' ?>
<main>
  <h2>Your Cart</h2>
  <section class="mb-10 mt-3">
    <table>
      <thead class="bg-green-700">
        <tr>
          <td>Product</td>
          <td>Price (HUF)</td>
          <td>Quantity</td>
          <td>Subtotal (HUF)</td>
        </tr>
      </thead>
      <tbody class="bg-gray-900">
        <?php foreach ($_SESSION["cart"] as $product): ?>
          <tr>
            <td><?= $product["name"] ?></td>
            <td><?= $product["price"] ?> HUF</td>
            <td><?= $product["quantity"] ?></td>
            <td><?= $product["price"] * $product["quantity"] ?> HUF</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h3 class="mb-2">Total: <?= $cart->getTotal() ?> HUF</h3>
    <button class="btn-long bg-green-700">Checkout</button>
  </section>
</main>
<?php require 'footer.php' ?>