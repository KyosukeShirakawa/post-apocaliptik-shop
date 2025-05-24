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

$id = $_GET["id"];
$user_storage = new UserStorage();
$user = $user_storage->findById($id);


require 'header.php' ?>
<main>
  <h2 class="mb-3">Welcome, <?= $user['username'] ?></h2>
  <p>Email: <?= $user['email'] ?></p>
  <section class="mb-10 mt-3">
    <h3 class="mb-3">Your Purchases</h3>
    <div class="record-container">
      <?php foreach ($user['purchases'] as $p) : ?>
        <div class="record">
          <p><?= $p['product'] ?> - <?= $p['quantity'] ?> pcs -<?= $p['date'] ?></p>
        </div>
      <?php endforeach ?>
    </div>
  </section>
</main>
<?php require 'footer.php' ?>