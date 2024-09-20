<?php
include 'db.php';

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if (mysqli_query($conn, $query)) {
        $message = "Registration successful.";
        $success = true;
    } else {
        $message = "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
    <style>
     
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="form-wrapper sign-up">
            <form action="register.php" method="post">
                <h2>Sign Up</h2>
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label for="">Username</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                </div>
                <div class="input-group">
                    <input type="email" name="email" required>
                    <label for="">Email</label>
                </div>               
                <button type="submit">Sign Up</button>
                <div class="signup">
                    <p>Already have an account? <a href="login.php">Log In</a></p>
                </div>
                <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                    <?php if (!empty($message)) { echo $message; } ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
