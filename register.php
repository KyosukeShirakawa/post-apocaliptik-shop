<?php
include_once('auth.php');
include_once('userStorage.php');

function validate($input, &$data, &$errors)
{
  if (!isset($input["username"])) {
    $errors["username"] = "username is required";
  } else if (empty(trim($input["username"]))) {
    $errors["username"] = "username cannot be empty";
  } else {
    $data["username"] = trim($input["username"]);
  }

  if (!isset($input["email"])) {
    $errors["email"] = "email is required";
  } else if (empty(trim($input["email"]))) {
    $errors["email"] = "email cannot be empty";
  } else if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "email is invalid";
  } else {
    $data["email"] = trim($input["email"]);
  }

  if (!isset($input["password"])) {
    $errors["password"] = "password is required";
  } else if (empty(trim($input["password"]))) {
    $errors["password"] = "password cannot be empty";
  } else {
    $data["password"] = trim($input["password"]);
  }

  return count($errors) === 0;
}

$errors = [];
$data = [];

$user_storage = new UserStorage();
$auth = new Auth($user_storage);


if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {
    if ($auth->user_exists($data['username'])) {
      $errors['global'] = "User already exists";
    } else {
      $auth->register($data);
      header("Location: login.php");
      exit();
    }
  }
}


require 'header.php';
?>
<main>
  <h2 class="mb-3 text-center">Register</h2>
  <div class="flex justify-center align-middle">

    <form id="loginForm" method="post" class="flex flex-col align-middle gap-4 mb-10" novalidate>
      <?php if (isset($errors['global'])) : ?>
        <p class="error text-center"><?= $errors['global'] ?></p>
      <?php endif; ?>
      <?php if (isset($errors['username'])) : ?>
        <p class="error text-center"><?= $errors['username'] ?></p>
      <?php endif; ?>
      <input type="text" name="username" value="" placeholder="Username">
      <?php if (isset($errors['email'])) : ?>
        <p class="error text-center"><?= $errors['email'] ?></p>
      <?php endif; ?>
      <input type="email" name="email" value="" placeholder="example@inf.elte.hu">
      <?php if (isset($errors['password'])) : ?>
        <p class="error text-center"><?= $errors['password'] ?></p>
      <?php endif; ?>
      <input type="text" name="password" value="" placeholder="Password">
      <button class="">Register</button>
    </form>
  </div>


</main>


<?php require 'footer.php' ?>