<?php
include 'connection.php';

// Buat instance dari class Database
$db = new Database();

class FormValidation {
    // Validasi Nama
    public static function validateNama($nama) {
        // Harus mengandung huruf kapital di setiap kata depan
        if (!preg_match('/^[A-Z][a-z]*(\s[A-Z][a-z]*)*$/', $nama)) {
            return "ERROR OCCURED: <=!!!=>Nama Harus Mengandung Huruf Kapital di Setiap Kata Depan<=!!!=>";
        }
        return "";
    }

    // Validasi Deskripsi
    public static function validateDeskripsi($deskripsi) {
        // Terdapat minimal 200 huruf atau bilangan yang menyusun kata/kalimat
        if (strlen($deskripsi) < 200) {
            return "ERROR OCCURED: <=!!!=>Deskripsi Harus Terdiri Dari Minimal 200 Karakter<=!!!=>";
        }
        return "";
    }

    // Validasi Alamat
    public static function validateAlamat($alamat) {
        // Harus terdapat kalimat yang mengandung "Jalan", "Kec.", dan "Kota Surakarta"
        // dengan kode pos yang mengandung 5 digit angka
        if (!preg_match('/Jalan/i', $alamat) || !preg_match('/Kec./i', $alamat) || !preg_match('/Kota Surakarta/i', $alamat) || !preg_match('/\b\d{5}\b/', $alamat)) {
            return "ERROR OCCURED: <=!!!=>Alamat Harus Mengandung Kalimat 'Jalan', 'Kec.', dan 'Kota Surakarta' dengan Kode Pos 5 Digit Angka<=!!!=>";
        }
        return "";
    }

    // Validasi Jam Buka
    public static function validateJamBuka($jam_buka) {
        // Jam buka harus diisi
        if (empty($jam_buka)) {
            return "ERROR OCCURED: <=!!!=>Jam Buka Harus Diisi<=!!!=>";
        }
        return "";
    }

    // Validasi biaya
    public static function validateBiaya($biaya) {
        // Minimal harus terdapat kalimat yang mengandung "Rp XX,XXX" atau "Gratis"
        if (!preg_match('/(Rp\s\d{1,3}(?:\,\d{3})+|\bGratis\b)/i', $biaya)) {
            return "ERROR OCCURED: <=!!!=>Biaya Harus Mengandung Format 'Rp XX,XXX' atau 'Gratis'<=!!!=>";
        }
        return "";
    }

    // Validasi Nama FIle Foto
    public static function validateFotoName($filename) {
        // Maksimal huruf atau angka yang merupakan nama dari foto adalah sebesar 50 karakter
        if (strlen($filename) > 50) {
            return "ERROR OCCURED: <=!!!=>Nama File Foto Tidak Boleh Lebih Dari 50 Karakter<=!!!=>";
        }
        return "";
    }

    // Validasi Format FIle Logo
    public static function validateLogoFormat($filename) {
        // Format: Format foto harus berupa (.png)
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'png') {
            return "ERROR OCCURED: <=!!!=>Format Logo Harus Berupa (.png)<=!!!=>";
        }
        return "";
    }
}
?>