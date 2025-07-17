<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="welcome-page">
    <div class="welcome-container">
        <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h2>

        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
        
    </div>
</body>
</html>
