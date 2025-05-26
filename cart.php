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

$user_storage = new UserStorage();


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
        <tr>
          <td>First Aid Kit</td>
          <td>8 500 HUF</td>
          <td>2</td>
          <td>17 000 HUF</td>
        </tr>
        <tr>
          <td>First Aid Kit</td>
          <td>8 500 HUF</td>
          <td>2</td>
          <td>17 000 HUF</td>
        </tr>
        <tr>
          <td>First Aid Kit</td>
          <td>8 500 HUF</td>
          <td>2</td>
          <td>17 000 HUF</td>
        </tr>
      </tbody>
    </table>

    <h3 class="mb-2">Total: 53 700 HUF</h3>
    <button class="btn-long bg-green-700">Checkout</button>
  </section>
</main>
<?php require 'footer.php' ?>