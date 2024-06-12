<!--///////////////////////////////
// Program Dibuat oleh :         //
// Elvizto Juan Khresnanda       //
// NIM : L0122054                //
// Kelas B                       //
////////////////////////////////-->

<?php 
    include "connection.php";

    // Buat instance dari class Database
    $db = new Database();

    $edit_id = '';
    $nama = '';
    $deskripsi = '';
    $alamat = '';
    $jam_buka = '';
    $biaya = '';

    if(isset($_GET['edit'])){
        $edit_id = $_GET['edit'];
        $jenisTempat = $_GET['jenisTempat'];

        if ($jenisTempat === 'destinations') {
            $query = "SELECT * FROM tb_destinasi WHERE id_destinasi = '$edit_id';";
        } elseif ($jenisTempat === 'restaurants') {
            $query = "SELECT * FROM tb_restoran WHERE id_restoran = '$edit_id';";
        } elseif ($jenisTempat === 'markets') {
            $query = "SELECT * FROM tb_pasar WHERE id_pasar = '$edit_id';";
        }

        // Menggunakan method select dari objek Database untuk menjalankan query
        $result = $db->select($query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
    
            // Mengisi variabel sesuai dengan jenis tempat yang sedang di-edit
            if ($jenisTempat === 'destinations') {
                $nama = $row['nama_destinasi'];
            } elseif ($jenisTempat === 'restaurants') {
                $nama = $row['nama_restoran'];
            } elseif ($jenisTempat === 'markets') {
                $nama = $row['nama_pasar'];
            }

            $deskripsi = $row['deskripsi'];
            $alamat = $row['alamat'];
            $jam_buka = $row['jam_buka'];
            $biaya = $row['biaya'];
            $logo = $row['logo'];
            $foto1 = $row['foto1'];
            $foto2 = $row['foto2'];
            $foto3 = $row['foto3'];
            $foto4 = $row['foto4'];
            $foto5 = $row['foto5'];
        }
    }
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

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1em;
            text-transform: uppercase;
            background-image: url('pic/BlueSky.jpg');
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

        /* CSS untuk Popup Message */
        #popupMessage {
            position: fixed;
            top: 4%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background-color: #242424;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
        }

        #popupContent {
            text-align: center;
        }

    </style>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
    <!-- Navbar Bootstrap -->
    <nav style="background-image: url('pic/BlueSky.jpg');" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="index.php">
            <i class="fa fa-snowflake"></i>
            Beranda
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <!-- Bagian Header -->
    <header style="padding-top: 75px;">
        <blockquote class="blockquote text-center">
            <h3 class="mb-0">Penambahan dan Perubahan Data</h3>
            <footers class="blockquote-footer">Pengimplementasian <cite title="Source Title">Create Read Update</cite></footers>
        </blockquote>
    </header>

    <!-- Bagian Content Body -->
    <div class="container">
        <form method="POST" action="process.php" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $edit_id ?>" name="edit_id">
            <div class="form-group row">
                <label for="jenisTempat" class="col-sm-2 col-form-label"><b>Jenis Tempat</b></label>
                <div class="col-sm-10">
                    <select required class="form-control" id="jenisTempat" name="jenisTempat" value="<?php echo $jenisTempat; ?>">
                        <option value="destinations">Destinations</option>
                        <option value="restaurants">Restaurants</option>
                        <option value="markets">Markets</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputNama" class="col-sm-2 col-form-label"><b>Nama</b></label>
                <div class="col-sm-10">
                    <input required type="text" class="form-control" id="inputNama" name="nama" placeholder="Nama..." value="<?php echo $nama; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputDeskripsi" class="col-sm-2 col-form-label"><b>Deskripsi</b></label>
                <div class="col-sm-10">
                    <textarea required type="text" class="form-control" id="inputDeskripsi" name="deskripsi" placeholder="Deskripsi..."><?php echo $deskripsi; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputAlamat" class="col-sm-2 col-form-label"><b>Alamat</b></label>
                <div class="col-sm-10">
                    <textarea required type="text" class="form-control" id="inputAlamat" name="alamat" placeholder="Alamat..."><?php echo $alamat; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputJamBuka" class="col-sm-2 col-form-label"><b>Jam Buka</b></label>
                <div class="col-sm-10">
                    <input required type="text" class="form-control" id="inputJamBuka" name="jam_buka" placeholder="Jam Buka..." value="<?php echo $jam_buka; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputBiaya" class="col-sm-2 col-form-label"><b>Biaya</b></label>
                <div class="col-sm-10">
                    <input required type="text" class="form-control" id="inputBiaya" name="biaya" placeholder="Biaya..." value="<?php echo $biaya; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputLogo" class="col-sm-2 col-form-label"><b>Logo Destinasi</b></label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input required type="file" class="custom-file-input" id="inputLogo" name="logo" accept="image/png" onchange="updateLabel(this)">
                        <label class="custom-file-label" for="inputLogo">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFoto1" class="col-sm-2 col-form-label"><b>Foto Destinasi 1</b></label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input required type="file" class="custom-file-input" id="inputFoto1" name="foto1" accept="image/*" onchange="updateLabel(this)">
                        <label class="custom-file-label" for="inputFoto1">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFoto2" class="col-sm-2 col-form-label"><b>Foto Destinasi 2</b></label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input required type="file" class="custom-file-input" id="inputFoto2" name="foto2" accept="image/*" onchange="updateLabel(this)">
                        <label class="custom-file-label" for="inputFoto2">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFoto3" class="col-sm-2 col-form-label"><b>Foto Destinasi 3</b></label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputFoto3" name="foto3" accept="image/*" onchange="updateLabel(this)">
                        <label class="custom-file-label" for="inputFoto3">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFoto4" class="col-sm-2 col-form-label"><b>Foto Destinasi 4</b></label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputFoto4" name="foto4" accept="image/*" onchange="updateLabel(this)">
                        <label class="custom-file-label" for="inputFoto4">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputFoto5" class="col-sm-2 col-form-label"><b>Foto Destinasi 5</b></label>
                <div class="col-sm-10">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputFoto5" name="foto5" accept="image/*" onchange="updateLabel(this)">
                        <label class="custom-file-label" for="inputFoto5">Choose file</label>
                    </div>
                </div>
            </div>
            <!-- Pemisahan Kondisi Create Update Delete -->
            <div class="form-group row mt-4">
                <div class="col">
                    <?php 
                        if(isset($_GET['edit'])){
                    ?>
                        <button type="submit" name="action" value="edit" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            SAVE CHANGES
                        </button>
                    <?php
                        } else {
                    ?>
                        <button type="submit" name="action" value="add" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            ADD
                        </button>
                    <?php
                        }
                    ?>
                    <a href="index.php" type="button" class="btn btn-danger">
                        <i class="fa fa-backward"></i>
                        CANCEL
                    </a>
                </div>
            </div>
        </form>
    </div>

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

    <!-- jQuery untuk Manipulasi DOM, animasi lebih lanjut, dan event handling -->
    <script>
        $(document).ready(function() {
            // Function to toggle alert "Found It!" pada navbar list
            $(".navbar-brand").on("click", function() {
                // Menampilkan alert "Found It!"
                showPopupMessage('Back To The Homepage!');
            });

            $(".btn-danger").on("click", function() {
                // Menampilkan alert "Found It!"
                showPopupMessage('Cancelling Data Modification!');
            });

            // Event Listener untuk Tautan di dalam footer
            $(".social-link").on("click", function(event) {
                event.preventDefault(); // Mencegah perilaku default dari tautan
                var url = $(this).attr("href");
                redirectToWebsite(url); // Panggil fungsi untuk membuka tautan di jendela baru
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var jenisTempatSelect = document.getElementById('jenisTempat');
            var inputNama = document.getElementById('inputNama');
            var selectedJenisTempat = '<?php echo isset($_GET["jenisTempat"]) ? $_GET["jenisTempat"] : "destinations"; ?>';

            jenisTempatSelect.value = selectedJenisTempat;

            jenisTempatSelect.addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex].value;

                if (selectedOption === 'destinations') {
                    inputNama.placeholder = 'Nama Destinasi...';
                } else if (selectedOption === 'restaurants') {
                    inputNama.placeholder = 'Nama Restoran...';
                } else if (selectedOption === 'markets') {
                    inputNama.placeholder = 'Nama Pasar...';
                }
            });
        });

        // Fungsi untuk mengupdate label penerima input file gambar
        function updateLabel(input) {
                var label = input.nextElementSibling;
                var fileName = input.files[0].name;
                label.innerText = fileName;
            }

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

        // Function to redirect to specified URL in the same window
        function redirectToMaps(url) {
            window.location.href = url; // membuka tautan dalam jendela yang sama
        }

        // Function to redirect to specified URL in a new window
        function redirectToWebsite(url) {
            window.open(url, '_blank'); // membuka tautan dalam jendela baru
        }
    </script>

</body>
</html>