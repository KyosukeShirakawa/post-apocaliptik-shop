<?php
include_once('auth.php');
include_once('productStorage.php');

function validate($input, &$data, &$errors)
{
  if (!isset($input["name"])) {
    $errors["name"] = "name is required";
  } else if (empty(trim($input["name"]))) {
    $errors["name"] = "name cannot be empty";
  } else {
    $data["name"] = trim($input["name"]);
  }

  if (!isset($input["price"])) {
    $errors["price"] = "price is required";
  } else if (empty(trim($input["price"]))) {
    $errors["price"] = "price cannot be empty";
  } else if (!is_numeric((int)trim($input["price"]))) {
    $errors["price"] = "Price has to be a number";
  } {
    $data["price"] = (int)trim($input["price"]);
  }

  if (!isset($input["category"])) {
    $errors["category"] = "category is required";
  } else if (empty(trim($input["category"]))) {
    $errors["category"] = "category cannot be empty";
  } else {
    $data["category"] = trim($input["category"]);
  }

  if (!isset($input["stock"])) {
    $errors["stock"] = "stock is required";
  } else if (empty(trim($input["stock"]))) {
    $errors["stock"] = "stock cannot be empty";
  } else if (!is_numeric((int)trim($input["stock"]))) {
    $errors["stock"] = "stock has to be a number";
  } {
    $data["stock"] = (int)trim($input["stock"]);
  }

  if (!isset($input["image"])) {
    $errors["image"] = "image url is required";
  } else if (empty(trim($input["image"]))) {
    $errors["image"] = "image url cannot be empty";
  } else if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', trim($input["image"]))) {
    $errors["image"] = "URL must point to an image (.jpg, .png, etc.).";
  } else {
    $data["image"] = trim($input["image"]);
  }

  if (!isset($input["description"])) {
    $errors["description"] = "description is required";
  } else if (empty(trim($input["description"]))) {
    $errors["description"] = "description cannot be empty";
  } else {
    $data["description"] = trim($input["description"]);
  }

  return count($errors) === 0;
}

$errors = [];
$data = [];

if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {
    $ps = new ProductStorage();
    $ps->add($data);
  }
}


require 'header.php';
?>
<main>
  <h2 class="mb-3 text-center">Add a new product</h2>
  <div class="flex justify-center align-middle">

    <form id="loginForm" method="post" class="flex flex-col align-middle gap-4 mb-10" novalidate>
      <?php if (isset($errors['global'])) : ?>
        <p class="error text-center"><?= $errors['global'] ?></p>
      <?php endif; ?>
      <?php if (isset($errors['name'])) : ?>
        <p class="error text-center"><?= $errors['name'] ?></p>
      <?php endif; ?>
      <input type="text" name="name" value="" placeholder="Product Name">
      <?php if (isset($errors['price'])) : ?>
        <p class="error text-center"><?= $errors['price'] ?></p>
      <?php endif; ?>
      <input type="number" name="price" value="" placeholder="price">
      <?php if (isset($errors['category'])) : ?>
        <p class="error text-center"><?= $errors['category'] ?></p>
      <?php endif; ?>
      <input type="text" name="category" value="" placeholder="category">
      <?php if (isset($errors['stock'])) : ?>
        <p class="error text-center"><?= $errors['stock'] ?></p>
      <?php endif; ?>
      <input type="number" name="stock" value="" placeholder="stock">
      <?php if (isset($errors['image'])) : ?>
        <p class="error text-center"><?= $errors['image'] ?></p>
      <?php endif; ?>
      <input type="url" name="image" value="" placeholder="image url">
      <?php if (isset($errors['description'])) : ?>
        <p class="error text-center"><?= $errors['description'] ?></p>
      <?php endif; ?>
      <textarea name="description" value="" placeholder="description"></textarea>
      <button class="">Add Product</button>
    </form>
  </div>


</main>


<?php require 'footer.php' ?>