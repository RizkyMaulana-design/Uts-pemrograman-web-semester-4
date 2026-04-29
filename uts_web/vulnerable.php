<?php
// Ubah "localhost" menjadi "localhost:3307" (sesuaikan dengan angka port di XAMPP kamu)
$conn = new mysqli("localhost:3307", "root", "", "eksperimen_keamanan");

$pesan = "";

// Cek apakah tombol login ditekan
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // QUERY YANG RENTAN (Tanpa Sanitasi)
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $pesan = "<h3 style='color:green;'>Login Berhasil! Selamat datang, Administrator.</h3>";
        // Di sini bisa ditambahkan echo query untuk screenshot di artikel
        $pesan .= "<p>Query yang dieksekusi: <br><code>$query</code></p>";
    } else {
        $pesan = "<h3 style='color:red;'>Login Gagal! Username atau password salah.</h3>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Rentan SQLi</title>
</head>

<body>
    <h2>Form Login (Vulnerable)</h2>
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