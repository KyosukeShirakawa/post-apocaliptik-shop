<?php
include_once("storage.php");

class Auth
{
  private $user_storage; // instance of user storage
  private $user = NULL; // stores authenticated user

  public function __construct(IStorage $user_storage)
  {
    $this->user_storage = $user_storage;

    if (isset($_SESSION["user"])) { // checks if a logged-in user exists in the session
      $this->user = $_SESSION["user"]; // sets user to currently authenticated user
    }
  }

  public function register($data)
  { // registers a user
    $user = [
      'username'  => $data['username'],
      'email' => $data['email'],
      'password'  => password_hash($data['password'], PASSWORD_DEFAULT), // encrypts the password using a secure hashing algorithm (bcrypt)
      'admin'  => false,
      "purchases"     => [],
    ];

    return $this->user_storage->add($user);
  }

  public function user_exists($username)
  { // checks if user exists
    $users = $this->user_storage->findOne(['username' => $username]);

    return !is_null($users);
  }

  public function authenticate($username, $password)
  { // authenticates user
    $users = $this->user_storage->findMany(function ($user) use ($username, $password) {
      return $user["username"] === $username &&
        password_verify($password, $user["password"]); // compares the provided password with the hashed password
    });

    return count($users) === 1 ? array_shift($users) : NULL; // ensures exactly one matching user exists
  }

  public function is_authenticated()
  {
    return isset($_SESSION["user"]); // checks if user is currently authenticated
    // return !is_null($this->user);
  }

  public function authorize($roles = [])
  { // checks if the authenticated user has the required roles
    if (!$this->is_authenticated()) {
      return FALSE;
    }
    foreach ($roles as $role) { // verifies if any required role matches the user's roles
      if (in_array($role, $this->user["roles"])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function login($user)
  { // logs in user (sets session data)
    $this->user = $user;
    $_SESSION["user"] = $user; // stores the user in the session
  }

  public function logout()
  { // logs out current user
    $this->user = NULL;
    unset($_SESSION["user"]);
  }

  public function authenticated_user()
  { // returns currently authenticated user
    return $this->user;
  }
}
