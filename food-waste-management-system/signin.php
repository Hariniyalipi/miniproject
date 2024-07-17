<?php
session_start();
include 'connection.php';

if (isset($_POST['sign'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $sanitized_emailid = mysqli_real_escape_string($connection, $email);
  $sanitized_password = mysqli_real_escape_string($connection, $password);

  $sql = "select * from login where email='$sanitized_emailid'";
  $result = mysqli_query($connection, $sql);
  $num = mysqli_num_rows($result);
  if ($num == 1) {
    while ($row = mysqli_fetch_assoc($result)) {
      if (password_verify($sanitized_password, $row['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['gender'] = $row['gender'];
        header("location:home.html");
      } else {
        echo "<h1><center> Login Failed incorrect password</center></h1>";
      }
    }
  } else {
    echo "<h1><center>Account does not exist </center></h1>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="index.css">
</head>
<header>
<body>
    <div class="login-form">
        <h2>Sign In</h2>
        <form method="POST" action="">
            <div class="input-container">
                <input type="text" name="email" required="required"/>
                <label for="email">Email</label>
            </div>
            <div class="input-container">
                <input type="password" name="password" required="required"/>
                <label for="password">Password</label>
            </div>
            <button type="submit" name="sign" class="login-btn">Login</button>
        </form>
    </div>
</body>
</header>
</html>