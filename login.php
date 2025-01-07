<?php
include 'db_connection.php';
session_start();
//check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = mysqli_real_escape_string($mysqli, $_POST['username']);
  $password = mysqli_real_escape_string($mysqli, $_POST['password']);

  //SQL query
  $sql = "SELECT userID FROM users WHERE username = '$username' AND password = '$password'";
  $result = $mysqli->query($sql);

  if ($result->num_rows == 1) {
      // Login successful
      $_SESSION['login_user'] = $username;
      header("location: home.php"); // Redirect to home page
  } else {
      echo '<script>alert("Username or Password is invalid");</script>';
  }
  $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
<!-- Link to Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="login_register.css">
<meta name="description" content="Use this page to login with a username and password.">
</head>
<body>

<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form action = "login.php" method = "post">
            <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" class="form-control" name = "username" id="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" name = "password" id="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full-width">Login</button>
            <button type="button" class="btn btn-secondary btn-full-width" onclick="redirectToReg()">Not a User? Click here to register.</button>
            <button type="button" class="btn btn-link btn-full-width" onclick="redirectToHome()">Continue as Guest</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function redirectToHome() {
        window.location.href = 'home.php';
    }
    function redirectToReg()
    {
      window.location.href = "register.php"
    }
</script>

</body>
</html>