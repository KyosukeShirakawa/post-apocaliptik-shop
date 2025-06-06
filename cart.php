<?php
session_start();

include_once("productStorage.php");
include_once("userStorage.php");
include_once("auth.php");

$auth = new Auth(new UserStorage());
if (!$auth->is_authenticated()) {
  header("Location: login.php");
  exit();
}

$ps = new ProductStorage();
$user_storage = new UserStorage();

require 'header.php' ?>
<main>
  <h2>Your Cart</h2>
  <section class="mb-10 mt-3">
    <?php if (isset($_SESSION['cart'])): ?>
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
          <?php $total = 0; ?>
          <?php foreach ($_SESSION["cart"] as $product_id => $quantity): ?>
            <?php
            $product = $ps->findById($product_id);
            $subtotal = $product["price"] * $quantity;
            $total += $subtotal;
            ?>
            <tr>
              <td><?= $product["name"] ?></td>
              <td><?= $product["price"] ?> HUF</td>
              <td><?= $quantity ?></td>
              <td><?= $subtotal ?> HUF</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <h3 class="mb-2">Total: <?= $total ?> HUF</h3>
      <a href="checkout.php" class="btn-long bg-green-700">Checkout</a>
    <?php else: ?>
      <p>your cart is empty</p>
    <?php endif; ?>
  </section>
</main>
<?php require 'footer.php' ?>