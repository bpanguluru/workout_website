<?php
include 'db_connection.php'; 
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    //check if username already exists
    $checkUser = "SELECT username FROM users WHERE username = '$username'";
    $userExist = $mysqli->query($checkUser);

    if ($userExist->num_rows == 0) {
        $sql = "INSERT INTO users (username, password, admin) VALUES ('$username', '$password', false)";
        if ($mysqli->query($sql) === TRUE) {
            $_SESSION['login_user'] = $username;
            header("location: index.php");
        } else {
            echo '<script>alert("Registration Error");</script>';
        }
    } else {
        echo '<script>alert("Username already exists. Please choose a different username.");</script>';
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
<meta name="description" content="Use this page to register as a new user using a unique username and a password.">
</head>
<body>

<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form action = "register.php" method = "post">
            <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" class="form-control" name = "username" id="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" name = "password" id="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full-width">Register</button>
            <button type="button" class="btn btn-link btn-full-width" onclick="redirectToHome()">Continue as Guest</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function redirectToHome() {
        window.location.href = 'index.php';
    }
</script>

</body>
</html>