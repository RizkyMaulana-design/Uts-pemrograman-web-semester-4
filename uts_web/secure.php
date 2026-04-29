<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "eksperimen_keamanan");

$pesan = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // KODE AMAN: Menggunakan Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // 'ss' berarti dua parameter string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pesan = "<h3 style='color:green;'>Login Berhasil! Selamat datang.</h3>";
    } else {
        $pesan = "<h3 style='color:red;'>Login Gagal! Username atau password salah.</h3>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Aman</title>
</head>

<body>
    <h2>Form Login (Aman)</h2>
    <?php echo $pesan; ?>
    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>
</body>

</html>