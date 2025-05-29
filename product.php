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
if (!isset($_GET["id"])) {
  header("Location: index.php");
  exit();
}

$id = $_GET["id"];

$ps = new ProductStorage();
$item = $ps->findById($id);
if (!$item) {
  header("Location: index.php");
  exit();
}

$us = new UserStorage();
$users = $us->findAll();

$purchaseMap = [];
foreach ($users as $user) {
  foreach ($user['purchases'] as $purchase) {
    if ($purchase['product'] === $item['name']) {
      $purchaseMap[] = [
        'username' => $user['username'],
        'quantity' => $purchase['quantity'],
        'date' => $purchase['date'],
      ];
    }
  }
}

usort($purchaseMap, function ($a, $b) {
  return strcasecmp($a['username'], $b['username']);
});

$userPurchase = array_filter($_SESSION["user"]["purchases"], function ($p) use ($item) {
  return $p['product'] === $item['name'];
});

require 'header.php' ?>
</body>
<main>
  <h2><?= $item["name"] ?></h2>
  <section class="mb-10 mt-3">
    <div class="flex gap-4 mb-5">
      <img class="rounded-sm w-44" src="./images/<?= $item["image"] ?>" alt="">
      <div class="flex flex-col gap-4">
        <p><?= $item["description"] ?></p>
        <p>Price: <?= $item["price"] ?> HUF</p>
        <p>Stock: <?= $item["stock"] ?> pcs</p>
      </div>
    </div>
    <?php if ($_SESSION["user"]["admin"]) : ?>
      <h3 class="mb-3"><?php ?>All Purchases</h3>
      <div id="record-container" class="mb-3 flex flex-col gap-2">
        <?php foreach ($purchaseMap as $p) : ?>
          <div class="record">
            <p><?= $p['username'] ?> - <?= $p['quantity'] ?> pcs -<?= $p['date'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>
      <a href="edit_product.php?id=<?= $id ?>" class="btn-long bg-blue-500">Edit Item</a>
    <?php else : ?>
      <h3 class="mb-3">Purchase History</h3>
      <div id="record-container" class="mb-3 flex flex-col gap-2">
        <?php foreach ($userPurchase as $p): ?>
          <div class="record">
            <p><?= $_SESSION["user"]["username"] ?> - <?= $p['quantity'] ?> pcs -<?= $p['date'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require 'footer.php' ?>