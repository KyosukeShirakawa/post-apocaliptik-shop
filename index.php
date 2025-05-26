<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once('userStorage.php');
include_once('productStorage.php');
include_once('auth.php');

$auth = new Auth(new UserStorage());
if (!$auth->is_authenticated()) {
  header("Location: login.php");
  exit();
}

$ps = new ProductStorage();
$products =  $ps->findAll();

// filter products
if (count($_GET) > 0) {
  $category = trim($_GET["category"]);
  $minPrice = (int)trim($_GET["minPrice"]);
  $maxPrice = (int)trim($_GET["maxPrice"]);
  if ($category) {
    $products = array_filter($products, function ($p) use ($category) {
      return $p["category"] == $category;
    });
  }
  if ($minPrice) {
    $products = array_filter($products, function ($p) use ($minPrice) {
      return $p["price"] >= $minPrice;
    });
  }
  if ($maxPrice) {
    $products = array_filter($products, function ($p) use ($maxPrice) {
      return $p["price"] <= $maxPrice;
    });
  }
}

require 'header.php';
?>
<main>
  <h2 class="mb-3">Products</h2>
  <form id="filterForm" method="get">
    <input type="text" name="category" value="" placeholder="Category">
    <input type="text" name="minPrice" value="" placeholder="Min price (HUF)">
    <input type="text" name="maxPrice" value="" placeholder="Max price (HUF)">
    <button id="filterBtn">Filter</button>
  </form>
  <section class="mb-10 mt-3">
    <div id="grid-container" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 p-5">
      <?php foreach ($products as $product): ?>
        <div class="card">
          <img src="./images/<?= $product["image"] ?>" alt="">
          <h4><?= $product["name"] ?></h4>
          <div class="card-description">
            <p>Price: <?= $product["price"] ?> HUF</p>
            <p>Stock: <?= $product["stock"] ?></p>
          </div>
          <div class="card-btns">
            <a href="./product.php?id=<?= $product["id"]; ?>" class="btn bg-green-700">View Details</a>
            <a class="btn <?php echo (int)$product["stock"] > 0 ?  "bg-gray-700" :  "bg-red-900" ?>"><?php echo (int)$product["stock"] > 0 ?  "Add to Cart" :  "Out of stock" ?></a>
            <a class="btn bg-blue-600">Edit Item</a>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </section>
</main>

<?php require 'footer.php' ?>