<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./includes/dist/output.css" rel="stylesheet">
  <title>Document</title>
</head>

<body>
  <header class="bg-green-700 px-5 py-2 mb-5 rounded-sm">
    <h1 class="text-xl text-white font-bold"><a href="index.php">PostApocaliptIK Shop</a></h1>
    <div class="flex justify-between text-amber-300">
      <div class="flex gap-5">
        <a href="index.php">Home</a>
        <?php if ($_SESSION["user"]["admin"]): ?>
          <a href="/add_product.php">Add New Item</a>
        <?php endif; ?>
      </div>
      <div class="flex gap-5">
        <a href="cart.php">Cart</a>
        <?php if ($_SESSION["user"]) : ?>
          <a href="logout.php">Logout</a>
        <?php else : ?>
          <a href="login.php">Login</a>
        <?php endif; ?>
        <a href="register.php">Register</a>
      </div>
    </div>
  </header>

</body>