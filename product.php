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
    <h3 class="mb-3">All Purchases</h3>
    <div id="record-container" class="mb-3 flex flex-col gap-2">
      <div class="record">
        <p>ShelterQueen23 - 1 pcs -2025.05.01</p>
      </div>
      <div class="record">
        <p>ShelterQueen23 - 1 pcs -2025.05.01</p>
      </div>
      <div class="record">
        <p>ShelterQueen23 - 1 pcs -2025.05.01</p>
      </div>
    </div>
    <button class="btn-long bg-blue-500">Edit Item</button>
  </section>
</main>
<?php require 'footer.php' ?>