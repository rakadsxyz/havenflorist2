<?php
// Memulai session untuk login user
session_start();

// Memanggil file koneksi ke database
include "koneksi.php";

// Memeriksa apakah tombol 'kirim' di klik dan data dikirim melalui metode POST
if (isset($_POST['kirim'])) {
    // Mengambil data yang dikirim dari form login.php dengan memeriksa apakah input tersedia
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Cek apakah email dan password tidak kosong
    if (empty($email) || empty($password)) {
        echo "Email atau Password tidak boleh kosong!";
        exit;
    }

    // Format acak password harus sama dengan proses_register.php
    $pengacak = "p3ng4c4k";
    $password_acak = md5($pengacak . md5($password) . $pengacak);

    // Menyeleksi data user dengan email dan password acak yang sesuai
    $query = "SELECT * FROM tb_user WHERE email = '$email' AND password = '$password_acak'";
    
    // Menjalankan query ditampung dalam variabel $hasil dan cek kesalahan query
    $hasil = mysqli_query($conn, $query);
    if (!$hasil) {
        die("Query gagal: " . mysqli_error($conn));
    }

    // Menangkap data dari hasil perintah query SQL
    $data = mysqli_fetch_array($hasil);
    $cek = mysqli_num_rows($hasil); // Menghitung jumlah data yang ditemukan

    // Cek apakah email dan password ditemukan pada database
    if ($cek > 0) {
        // Cek jika user login sebagai admin
        if ($data['role'] == "admin") {
            // Buat session login dan email
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "admin";
            // Alihkan ke halaman dashboard admin
            header("Location:dhasboard admin.html");
            exit();
        } elseif ($data['role'] == "user") {
            // Buat session login dan email
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "user";
            // Alihkan ke halaman dashboard user
            header("Location: index.html");
            exit();
        } else {
            // Jika role tidak dikenali
            echo "Anda bukan Admin dan bukan User.";
        }
    } else {
        // Jika email dan password tidak ditemukan pada database
        echo "GAGAL LOGIN!!!, Email dan Password tidak ditemukan.";
    }
} else {
    echo "Terjadi kesalahan dalam proses login.";
}
?>