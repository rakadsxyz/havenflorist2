<?php
// Memanggil file koneksi.php
include "koneksi.php";

// Mengambil data dari form
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$role = "user"; // Level otomatis diisi 'user' pada saat registrasi

// Format acak password harus sama dengan proses_login.php
$pengacak = "p3ng4c4k";
$password_acak = md5($pengacak . md5($password) . $pengacak);

if (isset($_POST['kirim'])) {
    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 0) {
        // Proses kirim data ke database MySQL, kolom 'id' tidak perlu disertakan
        $query = "INSERT INTO tb_user (email, password, phone, role) 
                  VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($query);
        $insert_stmt->bind_param("ssss", $email, $password_acak, $phone, $role);
        $hasil = $insert_stmt->execute();

        if ($hasil) {
            // Redirect ke halaman login setelah berhasil registrasi
            header("Location: log in.html");
            exit; // Tambahkan exit setelah header untuk menghentikan eksekusi script lebih lanjut
        } else {
            // Menampilkan error MySQL
            echo "Registrasi User Gagal! Error: " . mysqli_error($conn);
        }
    } else {
        echo "Email sudah terdaftar!";
    }

    // Tutup statement
    $stmt->close();
    $insert_stmt->close();
} else {
    echo "Terjadi kesalahan dalam proses registrasi!";
}

// Tutup koneksi
$conn->close();
?>
