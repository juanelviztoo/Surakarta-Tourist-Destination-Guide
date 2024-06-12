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

    // Memeriksa apakah user sudah login
    if(isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }

    // Inisialisasi pesan error
    $error = '';

    // Menggunakan koneksi ke database user
    $db_user = new Database();

    // Check if the "Remember Me" in user cookie is set
    if(isset($_COOKIE['logged_in_user'])) {
        // Redirect to index page if cookie is set
        header("Location: index.php");
        exit();
    }

    // Check if email is saved in cookie
    $saved_email = '';
    if(isset($_COOKIE['remembered_email'])) {
        $saved_email = $_COOKIE['remembered_email'];
    }

    // Memeriksa apakah form telah disubmit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = $db_user->escapeStringUser($_POST['email']);
            $password = $_POST['password'];

            // Ambil data user berdasarkan email
            $result = $db_user->selectUser("SELECT * FROM tb_myaccount WHERE email='$email'");
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    // Login sukses, set session
                    $_SESSION['username'] = $user['username'];
                    // set cookie if "Remember Me" is checked
                    if(isset($_POST['remember'])) {
                        setcookie("logged_in_user", $email, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
                        setcookie("remembered_email", $email, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
                    } else {
                        // Delete remembered email cookie if "Remember Me" is not checked
                        setcookie("remembered_email", "", time() - 3600, "/");
                    }
                    // Redirect ke halaman index
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Password Yang Anda Masukkan Salah. Tolong Masukkan Password Yang Benar.";
                }
            } else {
                $error = "Email Tidak Ditemukan. Tolong Masukkan Email Yang Benar.";
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
    <title>Login Form</title>
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
        <h2 class="mb-4">LOGIN YOUR ACCOUNT NOW</h2>
        <?php if (!empty($error)) { ?>
            <div id="errorAlert" class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Please Enter Your Current E-Mail..." value="<?php echo $saved_email; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Please Enter Your Password..." required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
            <button type="submit" value="login" class="btn btn-primary">Login</button>
        </form>
        <div class="mt-3 text-center">
            Don't Have an Account? <a href="registration.php">Register Now!</a>
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