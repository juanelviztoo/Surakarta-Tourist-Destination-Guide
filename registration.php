<!--///////////////////////////////
// Program Dibuat oleh :         //
// Elvizto Juan Khresnanda       //
// NIM : L0122054                //
// Kelas B                       //
////////////////////////////////-->

<?php
    session_start();
    // Include koneksi ke database
    include "connection.php";

    // Memeriksa apakah user sudah registrasi
    if(isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }

    // Menggunakan koneksi ke database user
    $db_user = new Database();

    // Inisialisasi pesan error
    $error = '';

    // Memeriksa apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Memastikan semua input terisi
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            // Memvalidasi email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email Tidak Valid.";
            } else {
                // Memeriksa apakah password sesuai
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $error = "Konfirmasi Password Tidak Sesuai.";
                } else {
                    // Memastikan password memenuhi kriteria
                    $uppercase = preg_match('@[A-Z]@', $_POST['password']);
                    $lowercase = preg_match('@[a-z]@', $_POST['password']);
                    $number    = preg_match('@[0-9]@', $_POST['password']);
                    if(!$uppercase || !$lowercase || !$number || strlen($_POST['password']) < 10) {
                        $error = "Password Harus Terdiri dari Minimal 10 Karakter dan Mengandung Huruf Kapital, Huruf Kecil, dan Angka.";
                    } else {
                        // Memeriksa apakah email sudah digunakan
                        $email = $db_user->escapeStringUser($_POST['email']);
                        $result = $db_user->selectUser("SELECT * FROM tb_myaccount WHERE email='$email'");
                        if(mysqli_num_rows($result) > 0) {
                            $error = "Email Sudah Digunakan. Gunakan Email Lainnya.";
                        } else {
                            // Memasukkan data ke database
                            $username = $db_user->escapeStringUser($_POST['username']);
                            $email = $db_user->escapeStringUser($_POST['email']);
                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                            $query = "INSERT INTO tb_myaccount (username, email, password) VALUES ('$username', '$email', '$password')";
                            if($db_user->queryUser($query)) {
                                // set cookie after successful registration
                                setcookie("registered_user", $username, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
                                // Redirect ke halaman login 
                                header("Location: login.php?registration_success=1");
                                exit();
                            } else {
                                $error = "Registrasi Gagal.";
                            }
                        }
                    }
                }
            }
        } else {
            $error = "Semua Form Harus Diisi.";
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* Style untuk bagian body */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            background-image: url('pic/BlueBuilding-HD.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

        /* Style untuk container form */
        .container-form {
            background-color: whitesmoke; /* Warna background untuk form */
            background-image: url('pic/WhitePlainBG2.jpg');
            border-radius: 8px; /* Radius sudut untuk form */
            padding: 25px; /* Padding di dalam form */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Style shadow untuk form */
            max-width: 950px; /* Lebar maksimum form */
            margin-top: 100px; /* Membuat form berada di tengah secara vertikal */
        }
    </style>
</head>
<body>
    <div class="container mt-5 container-form">
        <h2 class="mb-4">REGISTER YOUR ACCOUNT NOW</h2>
        <?php if (!empty($error)) { ?>
            <div id="errorAlert" class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Please Enter Your Name..." required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Please Enter Your Current E-Mail..." required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Please Enter Your Preference Password..." required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Please Retype The Password You Have Inputted..." required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye" id="toggleConfirmPassword" onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPassword')"></i>
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" value="register" class="btn btn-primary">Register</button>
        </form>
        <div class="mt-3 text-center">
            Already Have an Account? <a href="login.php">Login Now!</a>
        </div>
    </div>

    <script>
    // Fungsi untuk menghilangkan pesan error setelah 7.5 detik
    setTimeout(function() {
        var errorAlert = document.getElementById('errorAlert');
        if (errorAlert) {
            errorAlert.style.display = 'none';
        }
    }, 7500);

    // Fungsi untuk menampilkan pesan error (alert)
    function togglePasswordVisibility(inputId, iconId) {
        var passwordInput = document.getElementById(inputId);
        var icon = document.getElementById(iconId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.className = "fas fa-eye-slash"; // Ganti icon menjadi mata tertutup
        } else {
            passwordInput.type = "password";
            icon.className = "fas fa-eye"; // Ganti icon menjadi mata terbuka
        }
    }
    </script>
</body>
</html>