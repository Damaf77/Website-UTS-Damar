<?php
session_start();

// Fungsi untuk memuat data pengguna dari file
function load_users() {
    if (file_exists('users.json')) {
        $json_data = file_get_contents('users.json');
        $users = json_decode($json_data, true);
    } else {
        $users = array(); // Jika file tidak ada, kembalikan array kosong
    }

    // Cek apakah akun default admin ada, jika tidak tambahkan
    $is_admin_exist = false;
    foreach ($users as $user) {
        if ($user['username'] == 'admin') {
            $is_admin_exist = true;
            break;
        }
    }

    // Jika akun admin tidak ada, tambahkan akun default admin
    if (!$is_admin_exist) {
        $users[] = array(
            'username' => 'admin',
            'password' => password_hash('password123', PASSWORD_DEFAULT) // Hash password default
        );
        save_users($users); // Simpan kembali dengan akun admin
    }

    return $users;
}

// Fungsi untuk menyimpan data pengguna ke file
function save_users($users) {
    $json_data = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents('users.json', $json_data);
}

// Fungsi untuk menghapus file users.json
function delete_users_file() {
    if (file_exists('users.json')) {
        unlink('users.json'); // Hapus file users.json
    }
}

// Inisialisasi array pengguna dari file
$users = load_users();

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_all'])) {
        // Hapus seluruh akun (file users.json)
        delete_users_file();
        $success = "Seluruh akun berhasil dihapus. Gunakan admin sebagai akun default";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $is_login_success = false;
        
        foreach ($users as $user) {
            if ($user['username'] == $username && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $is_login_success = true;
                break;
            }
        }
        
        if ($is_login_success) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
    <div class="form-container">
        <div class="user-icon"></div>
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </form>
        <form action="login.php" method="POST" style="margin-top: 20px;">
            <button type="submit" name="delete_all" onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh akun?')">Hapus Seluruh Akun</button>
        </form>
    </div>
    <script src="assets/script.js"></script>
</body>
</html>
