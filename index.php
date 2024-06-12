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
    if(!isset($_SESSION['username']) && !isset($_COOKIE['logged_in_user'])) {
        header("Location: login.php");
        exit();
    }

    // Logout user
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
        // Hapus session
        session_unset();
        session_destroy();
        // Hapus cookie 'logged_in_user' jika ada
        if(isset($_COOKIE['logged_in_user'])) {
            setcookie('logged_in_user', '', time() - 3600, '/'); // Set waktu kedaluwarsa cookie ke masa lalu
        }
        // Redirect ke halaman login dengan pesan logout
        header("Location: login.php?logout=1");
        exit();
    }

    // Ambil nama pengguna dari session
    $username = $_SESSION['username'];

    // Buat instance dari class Database
    $db = new Database();

    // Query untuk navbar dropdown dan searching box
    $sql_destinations_navbar = $db->select("SELECT * FROM tb_destinasi");
    $sql_restaurants_navbar = $db->select("SELECT * FROM tb_restoran");
    $sql_markets_navbar = $db->select("SELECT * FROM tb_pasar");

    // Query untuk konten body
    $sql_destinations_body = $db->select("SELECT * FROM tb_destinasi");
    $sql_restaurants_body = $db->select("SELECT * FROM tb_restoran");
    $sql_markets_body = $db->select("SELECT * FROM tb_pasar");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Using CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.css">
    <title>Surakarta Tour Guidance</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            background-image: url('pic/WhitePlainBG.jpg');
            background-size: cover;
            background-repeat: round;
            background-position: center;
        }

        header {
            position: relative; /* Tambahkan position relative */
            text-transform: uppercase;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Warna overlay dengan opacity */
            z-index: 1;
        }

        header a {
            padding: 10px 20px;
            display: block;
            cursor: pointer;
            transition: background-color 0.3s ease, opacity 0.3s ease;
            background-image: url('pic/BlueSky.jpg');
        }

        header a:hover {
            opacity: 0.8;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1em;
            text-transform: uppercase;
            background-image: url('pic/BlueSky.jpg');
        }

        section {
            margin: 20px;
        }

        /* Grid CSS Styling */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
        }

        /* Penggunaan Box Model */
        .grid {
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            background-image: url('pic/WhitePlainBG2.jpg');
        }

        .destination, .restaurant, .market {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-image: url('pic/WhitePlainBG2.jpg');
        }

        /* Button CSS Styling */
        button {
            background-color: #333;
            color: #fff;
            padding: 10px 10px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        /* Tambahan CSS untuk tata letak responsif dan styling Carousel */
        .carousel {
            width: 100%;
            overflow: hidden;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
        }

        .carousel-inner {
            display: flex;
            flex-wrap: nowrap;
        }

        .carousel-item {
            flex: 0 0 auto;
            width: 100%;
            transition: transform 0.5s ease;
        }

        .carousel-item img {
            transition: transform 0.3s ease-in-out;
            width: 100%;
            height: auto;
        }

        .carousel-item img:hover {
            transform: scale(1.1);
        }

        .hidden {
            display: none;
        }

        /* CSS untuk ikon sosial media pada Footer */
        .social-icons {
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .social-icons a {
            color: #fff;
            margin: 0 15px;
            font-size: 28px;
        }

        /* CSS untuk Booking Form Content */
        .bookingImage{
            width: 75%;
            height: auto;
            margin: 0 auto;
            display: block;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .bookingImage:hover {
            transform: scale(0.9);
        }

        h4{
            text-align: center;
            margin-right: 10px;
        }

        .restaurant-icons{
            text-align: center;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .restaurant-icons a{
            display: inline-block; /* Membuat ikon dan teks berada dalam satu baris */
            margin-right: 10px; /* Memberikan jarak antara ikon */
            color: #333; /* Warna ikon */
            text-decoration: none; /* Menghilangkan garis bawah pada teks */
        }

        .restaurant-icons a:hover {
            color: #555; /* Warna ikon saat dihover */
        }

        /* CSS untuk Animasi dari Button Booking Form Content */
        @keyframes slideDown {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(0);
            }
        }

        .bookingForm {
            animation: slideDown 0.7s ease forwards;
            transform: translateY(-100%);
        }

        /* CSS untuk Popup Message */
        #popupMessage {
            position: fixed;
            top: 4%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background-color: #242424;
            color: #fff;
            padding: 13px;
            border-radius: 10px;
        }

        #popupContent {
            text-align: center;
        }

        /* CSS untuk Parallax Effect */
        .parallax-content {
            padding: 300px 0; /* Sesuaikan padding sesuai kebutuhan */
            text-align: center;
            color: #fff; /* Warna teks */
            position: relative;
            z-index: 2;
            height: 100vh;
            flex-direction: column;
        }

        /* CSS untuk Button Logout */
        .logout-btn i {
            margin-right: 5px; /* Jarak antara ikon dan teks */
        }
    </style>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
    <!-- Navbar Bootstrap -->
    <nav style="background-image: url('pic/BlueSky.jpg');" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Beranda</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Dropdown List Navbar -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDestinations" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Destinations</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownDestinations">
                        <?php
                            while($destination = mysqli_fetch_assoc($sql_destinations_navbar)) {
                                echo '<a class="dropdown-item" href="#' . 'destination_' . $destination['id_destinasi'] . '">' . $destination['nama_destinasi'] . '</a>';
                            }
                        ?>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRestaurants" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Restaurants</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownRestaurants">
                        <?php
                            while($restaurant = mysqli_fetch_assoc($sql_restaurants_navbar)) {
                                echo '<a class="dropdown-item" href="#' . 'restaurant_' . $restaurant['id_restoran'] . '">' . $restaurant['nama_restoran'] . '</a>';
                            }
                        ?>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownHotels" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Markets</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownHotels">
                        <?php
                            while($market = mysqli_fetch_assoc($sql_markets_navbar)) {
                                echo '<a class="dropdown-item" href="#' . 'market_' . $market['id_pasar'] . '">' . $market['nama_pasar'] . '</a>';
                            }
                        ?>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Adding Search Bar -->
        <form class="form-inline my-2 my-lg-1 ml-auto" id="searchForm">
            <input class="form-control mr-sm-2" type="search" placeholder="Cari Destinasi..." aria-label="Search" id="searchInput">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
        </form>

        <!-- Button Logout -->
        <form method="post" class="mr-auto">
            <button type="submit" name="logout" class="btn btn-danger ml-2"> 
                <i class="bi bi-person-fill"></i> LOGOUT
            </button>
        </form>
    </nav>

    <!-- Bagian Header dengan Parallax Effect -->
    <header style="padding-top: 25px; background-image: url('pic/SurakartaBackground1.jpg'); background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover;">
        <div class="parallax-content">
            <h1>Surakarta Tour Guidance</h1>
            <!-- Adding Welcoming Speech -->
            <div class="container">
                <h3 style="margin-bottom:5px;">Welcome, <?php echo $username; ?>!</h3>
            </div>
            <p>Temukan Destinasi Terbaikmu Di Kota Solo</p>
            <a href="#destinations" class="btn btn-primary btn-lg">Explore Now</a>
        </div>
    </header>

    <!-- Destinations Section -->
    <section id="destinations" class="destinations">
        <h2>Tourist Destinations</h2>
        <a href="manage.php?create&jenisTempat=destinations" type="button" class="btn btn-dark" style="margin-bottom: 10px;">
            <i class="fa fa-plus"></i>
            Add Destinations Data
        </a>
        <?php
            // Membaca data destinasi dari tabel tb_destinasi [READ]
            while($result = mysqli_fetch_assoc($sql_destinations_body)){
        ?>
        <!-- Keraton Solo, Pura Mangkunegaran, Museum Lokananta -->
        <article id="<?php echo 'destination_'. $result['id_destinasi']; ?>" class="destination grid-container">
            <div class="grid">
                <h3>
                    <img src="pic/<?php echo $result['logo']; ?>" style="width: 35px;">
                    <?php echo $result['nama_destinasi']; ?>
                    <a href="process.php?remove=<?php echo $result['id_destinasi']; ?>&jenisTempat=destinations" type="button" class="btn btn-danger" style="float: right; margin-left: 10px;" onclick="return confirm('Are you sure you want to delete this destination?!')">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="manage.php?edit=<?php echo $result['id_destinasi']; ?>&jenisTempat=destinations" type="button" class="btn btn-success" style="float: right;">
                        <i class="fa fa-pen"></i>
                    </a>
                </h3><hr>
                <p style="text-align: justify;">
                    <?php echo $result['deskripsi']; ?>
                </p>
                <p><b>Alamat:</b> <?php echo $result['alamat']; ?> </p>
                <p><b>Jam Buka:</b> <?php echo $result['jam_buka']; ?> </p>
                <p><b>Biaya:</b> <?php echo $result['biaya']; ?> </p>
            </div>
            <!-- Carousel untuk Slide Picture -->
            <div class="grid">
                <div id="carousel<?php echo str_replace(' ', '', $result['nama_destinasi']); ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                            // Periksa dan tampilkan gambar yang ada
                            for($i = 1; $i <= 5; $i++) {
                                if(!empty($result['foto'.$i])) {
                        ?>
                        <div class="carousel-item <?php echo ($i == 1) ? 'active' : ''; ?>" style="margin-top: <?php echo ($i == 1) ? '0' : '10'; ?>px;">
                            <img class="d-block w-100" src="pic/<?php echo $result['foto'.$i]; ?>" alt="<?php echo $result['nama_destinasi'].' '.$i; ?>">
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                    <!-- Carousel Prev & Next Button -->
                    <a class="carousel-control-prev" href="#carousel<?php echo str_replace(' ', '', $result['nama_destinasi']); ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel<?php echo str_replace(' ', '', $result['nama_destinasi']); ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </article>
        <?php
            }
        ?>
    </section>

    <!-- Restaurants Section -->
    <section id="restaurants" class="restaurants">
        <h2>Best Restaurants</h2>
        <a href="manage.php?create&jenisTempat=restaurants" type="button" class="btn btn-dark" style="margin-bottom: 10px;">
            <i class="fa fa-plus"></i>
            Add Restaurants Data
        </a>
        <?php
            // Membaca data destinasi dari tabel tb_restoran [READ]
            while($result = mysqli_fetch_assoc($sql_restaurants_body)){
        ?>
        <!-- Canting Londo Kitchen, Kusuma Sari, Adem Ayem -->
        <article id="<?php echo 'restaurant_'. $result['id_restoran']; ?>" class="restaurant grid-container">
            <div class="grid">
                <h3>
                    <img src="pic/<?php echo $result['logo']; ?>" style="width: 45px;">
                    <?php echo $result['nama_restoran']; ?>
                    <a href="process.php?remove=<?php echo $result['id_restoran']; ?>&jenisTempat=restaurants" type="button" class="btn btn-danger" style="float: right; margin-left: 10px;" onclick="return confirm('Are you sure you want to delete this restaurant?!')">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="manage.php?edit=<?php echo $result['id_restoran']; ?>&jenisTempat=restaurants" type="button" class="btn btn-success" style="float: right;">
                        <i class="fa fa-pen"></i>
                    </a>
                </h3><hr>
                <p style="text-align: justify;">
                    <?php echo $result['deskripsi']; ?>
                </p>
                <p><b>Alamat:</b> <?php echo $result['alamat']; ?> </p>
                <p><b>Jam Buka:</b> <?php echo $result['jam_buka']; ?> </p>
                <p><b>Biaya:</b> <?php echo $result['biaya']; ?> </p>
            </div>
            <!-- Carousel untuk Slide Picture -->
            <div class="grid">
                <div id="carousel<?php echo str_replace(' ', '', $result['nama_restoran']); ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                            // Periksa dan tampilkan gambar yang ada
                            for($i = 1; $i <= 5; $i++) {
                                if(!empty($result['foto'.$i])) {
                        ?>
                        <div class="carousel-item <?php echo ($i == 1) ? 'active' : ''; ?>" style="margin-top: <?php echo ($i == 1) ? '0' : '5'; ?>px;">
                            <img class="d-block w-100" src="pic/<?php echo $result['foto'.$i]; ?>" alt="<?php echo $result['nama_restoran'].' '.$i; ?>">
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                    <!-- Carousel Prev & Next Button -->
                    <a class="carousel-control-prev" href="#carousel<?php echo str_replace(' ', '', $result['nama_restoran']); ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel<?php echo str_replace(' ', '', $result['nama_restoran']); ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </article>
        <?php
            }
        ?>
    </section>


    <!-- Markets Section -->
    <section id="markets" class="markets">
        <h2>Solo Raya Markets</h2>
        <a href="manage.php?create&jenisTempat=markets" type="button" class="btn btn-dark" style="margin-bottom: 10px;">
            <i class="fa fa-plus"></i>
            Add Markets Data
        </a>
        <?php
            // Membaca data destinasi dari tabel tb_pasar [READ]
            while($result = mysqli_fetch_assoc($sql_markets_body)){
        ?>
        <!-- Pasar Triwindu, Ngarsopuro Night Market, Pasar Gedhe Harjonagoro -->
        <article id="<?php echo 'market_'. $result['id_pasar']; ?>" class="market grid-container">
            <div class="grid">
                <h3>
                    <img src="pic/<?php echo $result['logo']; ?>" style="width: 45px;">
                    <?php echo $result['nama_pasar']; ?>
                    <a href="process.php?remove=<?php echo $result['id_pasar']; ?>&jenisTempat=markets" type="button" class="btn btn-danger" style="float: right; margin-left: 10px;" onclick="return confirm('Are you sure you want to delete this market?!')">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="manage.php?edit=<?php echo $result['id_pasar']; ?>&jenisTempat=markets" type="button" class="btn btn-success" style="float: right;">
                        <i class="fa fa-pen"></i>
                    </a>
                </h3><hr>
                <p style="text-align: justify;">
                    <?php echo $result['deskripsi']; ?>
                </p>
                <p><b>Alamat:</b> <?php echo $result['alamat']; ?> </p>
                <p><b>Jam Buka:</b> <?php echo $result['jam_buka']; ?> </p>
                <p><b>Biaya:</b> <?php echo $result['biaya']; ?> </p>
            </div>
            <!-- Carousel untuk Slide Picture -->
            <div class="grid">
                <div id="carousel<?php echo str_replace(' ', '', $result['nama_pasar']); ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                            // Periksa dan tampilkan gambar yang ada
                            for($i = 1; $i <= 5; $i++) {
                                if(!empty($result['foto'.$i])) {
                        ?>
                        <div class="carousel-item <?php echo ($i == 1) ? 'active' : ''; ?>" style="margin-top: <?php echo ($i == 1) ? '30' : '0'; ?>px;">
                            <img class="d-block w-100" src="pic/<?php echo $result['foto'.$i]; ?>" alt="<?php echo $result['nama_pasar'].' '.$i; ?>">
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </div>
                    <!-- Carousel Prev & Next Button -->
                    <a class="carousel-control-prev" href="#carousel<?php echo str_replace(' ', '', $result['nama_pasar']); ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel<?php echo str_replace(' ', '', $result['nama_pasar']); ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </article>
        <?php
            }
        ?>
    </section>

    <!-- Bagian Footer -->
    <footer>
        <div class="social-icons">
            <!-- WhatsApp -->
            <a href="https://wa.me/6282324772644" class="social-link"><i class="fab fa-whatsapp"></i></a>
            <!-- Instagram -->
            <a href="https://www.instagram.com/elviztojuan._.k/" class="social-link"><i class="fab fa-instagram"></i></a>
            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/in/elvizto-juan-khresnanda-29896525b/" class="social-link"><i class="fab fa-linkedin"></i></a>
        </div>
        <p>Walking On Surakarta &copy; 2024</p>
    </footer>

    <!-- Popup message container -->
    <div id="popupMessage" class="hidden">
        <div id="popupContent">
            <!-- Popup message content will be displayed here -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Typeahead.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <!-- Parsley.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <!-- Rellax.js for parallax effect -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rellax/1.12.1/rellax.min.js"></script>

    <!-- jQuery untuk Manipulasi DOM, aksi konten navbar lebih lanjut, dan event handling -->
    <script>
        $(document).ready(function() {
            // Function to toggle alert "Found It!" pada navbar list
            $(".dropdown-item").on("click", function() {
                // Menampilkan alert "Found It!"
                showPopupMessage('Found It. Here We Go!');
            });

            // Event Listener untuk Tautan di dalam footer
            $(".social-link").on("click", function(event) {
                event.preventDefault(); // Mencegah perilaku default dari tautan
                var url = $(this).attr("href");
                redirectToWebsite(url); // Panggil fungsi untuk membuka tautan di jendela baru
            });

            // Initialize Typeahead.js for autocomplete
            $('#searchInput').typeahead({
                source: [
                    <?php
                        // Mengambil semua tempat (destinasi, restoran, pasar)
                        $places = [];
                        $places_result = $db->select("SELECT nama_destinasi AS place FROM tb_destinasi UNION SELECT nama_restoran AS place FROM tb_restoran UNION SELECT nama_pasar AS place FROM tb_pasar;");
                        while($place = mysqli_fetch_assoc($places_result)) {
                            $places[] = "'" . $place['place'] . "'";
                        }
                        echo implode(',', $places);
                    ?>
                ]
            });

            // Initialize Parsley.js for form validation
            $('#searchForm').parsley();

            // Event listener untuk form submission
            $('#searchForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submission default
                var query = $('#searchInput').val().toLowerCase();

                // Cek destinasi
                <?php
                    mysqli_data_seek($sql_destinations_navbar, 0);
                    while($destination = mysqli_fetch_assoc($sql_destinations_navbar)) {
                        echo "if (query === '" . strtolower($destination['nama_destinasi']) . "') {";
                        echo "showPopupMessage('Redirecting to " . $destination['nama_destinasi'] . "...');";
                        echo "window.location.href = '#' + 'destination_' + " . $destination['id_destinasi'] . ";";
                        echo "return;";
                        echo "}";
                    }
                ?>

                // Cek restoran
                <?php
                    mysqli_data_seek($sql_restaurants_navbar, 0);
                    while($restaurant = mysqli_fetch_assoc($sql_restaurants_navbar)) {
                        echo "if (query === '" . strtolower($restaurant['nama_restoran']) . "') {";
                        echo "showPopupMessage('Redirecting to " . $restaurant['nama_restoran'] . "...');";
                        echo "window.location.href = '#' + 'restaurant_' + " . $restaurant['id_restoran'] . ";"; // Gunakan 'restaurant_' sebagai prefix
                        echo "return;";
                        echo "}";
                    }
                ?>

                // Cek pasar
                <?php
                    mysqli_data_seek($sql_markets_navbar, 0);
                    while($market = mysqli_fetch_assoc($sql_markets_navbar)) {
                        echo "if (query === '" . strtolower($market['nama_pasar']) . "') {";
                        echo "showPopupMessage('Redirecting to " . $market['nama_pasar'] . "...');";
                        echo "window.location.href = '#' + 'market_' + " . $market['id_pasar'] . ";"; // Gunakan 'market_' sebagai prefix
                        echo "return;";
                        echo "}";
                    }
                ?>

                // Jika tidak ditemukan, tampilkan pesan
                alert('Tempat Tujuan Tidak Ditemukan! Silahkan Coba Lagi!');
            });
        });

        // Function to show popup message
        function showPopupMessage(message) {
            // Create popup message container
            var popupContainer = document.getElementById("popupMessage");
            // Set the message content
            popupContainer.innerHTML = '<div id="popupContent">' + message + '</div>';
            // Show the popup message
            popupContainer.classList.remove("hidden");
            // Set timeout to hide the popup after 3 seconds
            setTimeout(function () {
                popupContainer.classList.add("hidden");
            }, 1400);
        }

        // Function to redirect to specified URL in a new window
        function redirectToWebsite(url) {
            window.open(url, '_blank'); // membuka tautan dalam jendela baru
        }

        // Function to display a warning before logging out
        document.addEventListener("DOMContentLoaded", function() {
            const logoutButton = document.querySelector("button[name='logout']");
            
            if (logoutButton) {
                logoutButton.addEventListener("click", function(event) {
                    if (!confirm('Apakah Anda Yakin Ingin Logout Dari Akun dan Keluar Dari Website?')) {
                        event.preventDefault(); // Batalkan tindakan bawaan tombol
                    }
                });
            }
        });
    </script>
</body>
</html>