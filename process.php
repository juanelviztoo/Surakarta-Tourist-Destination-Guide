<!--///////////////////////////////
// Program Dibuat oleh :         //
// Elvizto Juan Khresnanda       //
// NIM : L0122054                //
// Kelas B                       //
////////////////////////////////-->

<?php
    include 'formvalidation.php';

    // DATA CREATE ACTION
    if(isset($_POST["action"])){
        if($_POST["action"] == "add"){

            // Inisialisasi variabel hasil data masukkan pada form sebelumnya
            $nama = $db->escapeString($_POST['nama']);
            $deskripsi = $db->escapeString($_POST['deskripsi']);
            $alamat = $db->escapeString($_POST['alamat']);
            $jam_buka = $db->escapeString($_POST['jam_buka']);
            $biaya = $db->escapeString($_POST['biaya']);
            $logo = $_FILES['logo']['name'];
            $foto1 = $_FILES['foto1']['name'];
            $foto2 = $_FILES['foto2']['name'];
            $foto3 = $_FILES['foto3']['name'];
            $foto4 = $_FILES['foto4']['name'];
            $foto5 = $_FILES['foto5']['name'];

            // Validasi form sebelum melakukan operasi CRUD
            $errors = array();

            // Validasi Nama
            $errors[] = FormValidation::validateNama($nama);

            // Validasi Deskripsi
            $errors[] = FormValidation::validateDeskripsi($deskripsi);

            // Validasi Alamat
            $errors[] = FormValidation::validateAlamat($alamat);

            // Validasi Jam Buka
            $errors[] = FormValidation::validateJamBuka($jam_buka);

            // Validasi biaya
            $errors[] = FormValidation::validateBiaya($biaya);

            // Validasi Nama File Foto
            $errors[] = FormValidation::validateFotoName($logo);
            $errors[] = FormValidation::validateFotoName($foto1);
            $errors[] = FormValidation::validateFotoName($foto2);
            $errors[] = FormValidation::validateFotoName($foto3);
            $errors[] = FormValidation::validateFotoName($foto4);
            $errors[] = FormValidation::validateFotoName($foto5);

            // Validasi Format File Logo
            $errors[] = FormValidation::validateLogoFormat($logo);

            // Filter out empty error messages
            $errors = array_filter($errors);

            // Jika tidak ada error, lakukan proses INSERT
            if (empty($errors)) {
                // Penyimpanan Gambar [FILE UPLOAD]
                $dir = "pic/";

                // Simpan Logo
                $tmpLogo = $_FILES['logo']['tmp_name'];
                move_uploaded_file($tmpLogo, $dir.$logo);

                // Simpan Foto 1
                $tmpFoto1 = $_FILES['foto1']['tmp_name'];
                move_uploaded_file($tmpFoto1, $dir.$foto1);

                // Simpan Foto 2
                $tmpFoto2 = $_FILES['foto2']['tmp_name'];
                move_uploaded_file($tmpFoto2, $dir.$foto2);

                // Simpan Foto 3
                $tmpFoto3 = $_FILES['foto3']['tmp_name'];
                move_uploaded_file($tmpFoto3, $dir.$foto3);

                // Simpan Foto 4
                $tmpFoto4 = $_FILES['foto4']['tmp_name'];
                move_uploaded_file($tmpFoto4, $dir.$foto4);

                // Simpan Foto 5
                $tmpFoto5 = $_FILES['foto5']['tmp_name'];
                move_uploaded_file($tmpFoto5, $dir.$foto5);

                // Mendapatkan nilai jenisTempat dari form
                $jenisTempat = $_POST['jenisTempat'];

                // Query untuk menentukan tabel tujuan berdasarkan jenisTempat
                switch($jenisTempat) {
                    case 'destinations':
                        $query = "INSERT INTO tb_destinasi VALUES (null, '$nama', '$deskripsi', '$alamat', '$jam_buka', '$biaya', '$logo', '$foto1', '$foto2', '$foto3', '$foto4', '$foto5')";
                        break;
                    case 'restaurants':
                        $query = "INSERT INTO tb_restoran VALUES (null, '$nama', '$deskripsi', '$alamat', '$jam_buka', '$biaya', '$logo', '$foto1', '$foto2', '$foto3', '$foto4', '$foto5')";
                        break;
                    case 'markets':
                        $query = "INSERT INTO tb_pasar VALUES (null, '$nama', '$deskripsi', '$alamat', '$jam_buka', '$biaya', '$logo', '$foto1', '$foto2', '$foto3', '$foto4', '$foto5')";
                        break;
                    default:
                        // Handle default case or error
                        break;
                }
            
                // Menjalankan query
                $result = $db->query($query);
            
                // Memeriksa apakah query berhasil dijalankan
                if($result) {
                    header("location: index.php");
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } else {
                // Jika terdapat error, tampilkan pesan error
                foreach ($errors as $error) {
                    echo $error . "<br>";
                }
            }

            // DATA UPDATE ACTION
            } else if($_POST["action"] == "edit"){
                // Inisialisasi variabel hasil data masukkan pada form sebelumnya
                $edit_id = $_POST['edit_id'];
                $jenisTempat = $_POST['jenisTempat'];
                $nama = $db->escapeString($_POST['nama']);
                $deskripsi = $db->escapeString($_POST['deskripsi']);
                $alamat = $db->escapeString($_POST['alamat']);
                $jam_buka = $db->escapeString($_POST['jam_buka']);
                $biaya = $db->escapeString($_POST['biaya']);
            
                // Validasi form sebelum melakukan operasi CRUD
                $errors = array();

                // Validasi Nama
                $errors[] = FormValidation::validateNama($nama);
            
                // Validasi Deskripsi
                $errors[] = FormValidation::validateDeskripsi($deskripsi);
            
                // Validasi Alamat
                $errors[] = FormValidation::validateAlamat($alamat);
            
                // Validasi Jam Buka
                $errors[] = FormValidation::validateJamBuka($jam_buka);
            
                // Validasi biaya
                $errors[] = FormValidation::validateBiaya($biaya);
            
                // Validasi Nama File Foto
                if (!empty($_FILES['logo']['name'])) {
                    $errors[] = FormValidation::validateFotoName($_FILES['logo']['name']);
                }
                if (!empty($_FILES['foto1']['name'])) {
                    $errors[] = FormValidation::validateFotoName($_FILES['foto1']['name']);
                }
                if (!empty($_FILES['foto2']['name'])) {
                    $errors[] = FormValidation::validateFotoName($_FILES['foto2']['name']);
                }
                if (!empty($_FILES['foto3']['name'])) {
                    $errors[] = FormValidation::validateFotoName($_FILES['foto3']['name']);
                }
                if (!empty($_FILES['foto4']['name'])) {
                    $errors[] = FormValidation::validateFotoName($_FILES['foto4']['name']);
                }
                if (!empty($_FILES['foto5']['name'])) {
                    $errors[] = FormValidation::validateFotoName($_FILES['foto5']['name']);
                }
            
                // Validasi Format File Logo
                if (!empty($_FILES['logo']['name'])) {
                    $errors[] = FormValidation::validateLogoFormat($_FILES['logo']['name']);
                }
            
                // Filter out empty error messages
                $errors = array_filter($errors);

            // Jika tidak ada error, lakukan proses UPDATE
            if (empty($errors)) {
                // Direktori penyimpanan gambar
                $dir = "pic/";

                // Loop untuk penanganan file gambar
                $gambarArray = array("logo", "foto1", "foto2", "foto3", "foto4", "foto5");
                foreach ($gambarArray as $gambar) {
                    if (!empty($_FILES[$gambar]['name'])) {
                        // Hapus gambar lama jika ada
                        if (!empty($_POST['old_' . $gambar])) {
                            unlink($dir . $_POST['old_' . $gambar]);
                        }
                        // Jika ada gambar yang diunggah, pindahkan dan simpan
                        move_uploaded_file($_FILES[$gambar]['tmp_name'], $dir . $_FILES[$gambar]['name']);
                    } else {
                        // Jika tidak ada gambar baru diunggah, gunakan yang lama
                        $_FILES[$gambar]['name'] = $_POST['old_' . $gambar];
                    }
                }

            
                // Query UPDATE sesuai dengan jenisTempat
                switch ($jenisTempat) {
                    case 'destinations':
                        $query = "UPDATE tb_destinasi SET nama_destinasi='$nama', deskripsi='$deskripsi', alamat='$alamat', jam_buka='$jam_buka', biaya='$biaya', 
                                    logo='{$_FILES['logo']['name']}', foto1='{$_FILES['foto1']['name']}', foto2='{$_FILES['foto2']['name']}', 
                                    foto3='{$_FILES['foto3']['name']}', foto4='{$_FILES['foto4']['name']}', foto5='{$_FILES['foto5']['name']}' WHERE id_destinasi='$edit_id'";
                        break;
                    case 'restaurants':
                        $query = "UPDATE tb_restoran SET nama_restoran='$nama', deskripsi='$deskripsi', alamat='$alamat', jam_buka='$jam_buka', biaya='$biaya', 
                                    logo='{$_FILES['logo']['name']}', foto1='{$_FILES['foto1']['name']}', foto2='{$_FILES['foto2']['name']}', 
                                    foto3='{$_FILES['foto3']['name']}', foto4='{$_FILES['foto4']['name']}', foto5='{$_FILES['foto5']['name']}' WHERE id_restoran='$edit_id'";
                        break;
                    case 'markets':
                        $query = "UPDATE tb_pasar SET nama_pasar='$nama', deskripsi='$deskripsi', alamat='$alamat', jam_buka='$jam_buka', biaya='$biaya', 
                                    logo='{$_FILES['logo']['name']}', foto1='{$_FILES['foto1']['name']}', foto2='{$_FILES['foto2']['name']}', 
                                    foto3='{$_FILES['foto3']['name']}', foto4='{$_FILES['foto4']['name']}', foto5='{$_FILES['foto5']['name']}' WHERE id_pasar='$edit_id'";
                        break;
                    default:
                        // Handle default case or error
                        break;
                }
            
                // Eksekusi query UPDATE
                $result = $db->query($query);
            
                // Redirect ke halaman index setelah pembaruan
                if ($result) {
                    header("location: index.php");
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            } else {
                // Jika terdapat error, tampilkan pesan error
                foreach ($errors as $error) {
                    echo $error . "<br>";
                }
            }
        }
    }

    // DATA DELETE ACTION
    if(isset($_GET['remove'])){
        $id_destinasi = $_GET['remove'];
        $id_restoran = $_GET['remove'];
        $id_pasar = $_GET['remove'];

        // Mendapatkan nilai jenisTempat dari laman index
        $jenisTempat = $_GET['jenisTempat'];

        // Query untuk menentukan tabel tujuan berdasarkan jenisTempat
        switch($jenisTempat) {
            case 'destinations':
                $querySelect = "SELECT * FROM tb_destinasi WHERE id_destinasi = '$id_destinasi'";
                $queryDelete = "DELETE FROM tb_destinasi WHERE id_destinasi = '$id_destinasi'";
                break;
            case 'restaurants':
                $querySelect = "SELECT * FROM tb_restoran WHERE id_restoran = '$id_restoran'";
                $queryDelete = "DELETE FROM tb_restoran WHERE id_restoran = '$id_restoran'";
                break;
            case 'markets':
                $querySelect = "SELECT * FROM tb_pasar WHERE id_pasar = '$id_pasar'";
                $queryDelete = "DELETE FROM tb_pasar WHERE id_pasar = '$id_pasar'";
                break;
            default:
                // Handle default case or error
                break;
        }

        // Eksekusi query untuk memilih data yang akan dihapus
        $resultSelect = $db->select($querySelect);
        $row = mysqli_fetch_assoc($resultSelect);

        // Hapus File Foto Terupload dari Direktori
        unlink("pic/".$row['logo']);
        unlink("pic/".$row['foto1']);
        unlink("pic/".$row['foto2']);
        unlink("pic/".$row['foto3']);
        unlink("pic/".$row['foto4']);
        unlink("pic/".$row['foto5']);

        // Eksekusi query untuk menghapus data dari database
        $resultDelete = $db->query($queryDelete);

        // Memeriksa apakah query berhasil dijalankan
        if($resultDelete) {
            header("location: index.php");
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
?>