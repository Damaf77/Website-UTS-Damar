<?php
session_start();

// Fungsi untuk memuat data pengguna dari file
function load_users() {
    if (file_exists('users.json')) {
        $json_data = file_get_contents('users.json');
        $users = json_decode($json_data, true);

        // Pastikan admin selalu ada
        $admin_exists = false;
        foreach ($users as $user) {
            if ($user['username'] == 'admin') {
                $admin_exists = true;
                break;
            }
        }

        // Jika admin tidak ada, tambahkan admin dengan password default
        if (!$admin_exists) {
            $users[] = array(
                'username' => 'admin',
                'password' => password_hash('password123', PASSWORD_DEFAULT)
            );
            save_users($users); // Simpan perubahan ke file
        }

        return $users;
    }

    // Jika file tidak ada, buat array dengan akun admin default
    $default_users = array(
        array(
            'username' => 'admin',
            'password' => password_hash('password123', PASSWORD_DEFAULT) // Hash password
        )
    );
    save_users($default_users); // Simpan admin default ke file
    return $default_users;
}

// Fungsi untuk menyimpan data pengguna ke file
function save_users($users) {
    $json_data = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents('users.json', $json_data);
}

// Inisialisasi array pengguna dari file
$users = load_users();

// Proses registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah username sudah ada
    $is_user_exist = false;
    foreach ($users as $user) {
        if ($user['username'] == $username) {
            $is_user_exist = true;
            break;
        }
    }

    if ($is_user_exist) {
        $error = "Username sudah ada! Silakan gunakan username lain.";
    } elseif ($password != $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        // Simpan pengguna baru ke dalam array
        $users[] = array(
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT) // Hash password
        );
        save_users($users); // Simpan pengguna ke dalam file
        $success = "Registrasi berhasil! Silahkan login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="register-page">
    <div class="form-container">
        <div class="user-icon"></div>
        <h2>Registrasi</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required>
            <button type="submit">Daftar</button>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </form>
    </div>
    <script src="assets/script.js"></script>
</body>
</html>
