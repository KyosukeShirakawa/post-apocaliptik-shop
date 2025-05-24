<?php
include_once('userstorage.php');
include_once('auth.php');

function validate($input, &$data, &$errors)
{
  if (!isset($input["username"])) {
    $errors["username"] = "username is required";
  } else if (empty(trim($input["username"]))) {
    $errors["username"] = "username cannot be empty";
  } else {
    $data["username"] = trim($input["username"]);
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

session_start();

$user_storage = new UserStorage();
$auth = new Auth($user_storage);

$data = [];
$errors = [];

if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {
    $auth_user = $auth->authenticate($data['username'], $data['password']);

    if (!$auth_user) {
      $errors['global'] = "Login error";
    } else {
      $auth->login($auth_user);
      header("Location: index.php");
      exit();
    }
  }
}


require 'header.php';
?>
<main>
  <h2 class="mb-3 text-center">Login</h2>
  <div class="flex justify-center align-middle">
    <form id="loginForm" method="post" class="flex flex-col align-middle gap-4 mb-10" novalidate>
      <input type="text" name="username" value="" placeholder="Username">
      <input type="text" name="password" value="" placeholder="Password">
      <button class="">Login</button>
    </form>
  </div>
</main>


<?php require 'footer.php' ?>