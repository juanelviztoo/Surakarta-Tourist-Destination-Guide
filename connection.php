<!--///////////////////////////////
// Program Dibuat oleh :         //
// Elvizto Juan Khresnanda       //
// NIM : L0122054                //
// Kelas B                       //
////////////////////////////////-->

<!-- This connection is for processing CRUD actions on the “db_destinasi” and “db_user” database in the website -->
<?php
    // Create a connection for database processing
    class Database {
        private $host = 'localhost';
        private $user = 'root';
        private $pass = '';
        private $db = 'db_destinasi';
        private $db_user = 'db_user';
        private $conn;
        private $conn_user;
    
        // Constructor
        public function __construct() {
            $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
            mysqli_select_db($this->conn, $this->db);

            $this->conn_user = mysqli_connect($this->host, $this->user, $this->pass, $this->db_user);
            mysqli_select_db($this->conn_user, $this->db_user);
        }

        // Method untuk menjalankan query SELECT di 'db_destinasi'
        public function select($query) {
            return mysqli_query($this->conn, $query);
        }

        // Method untuk menjalankan query SELECT di 'db_user'
        public function selectUser($query) {
            return mysqli_query($this->conn_user, $query);
        }

        // Method untuk menjalankan query INSERT, UPDATE, DELETE 'db_destinasi'
        public function query($query) {
            return mysqli_query($this->conn, $query);
        }

        // Method untuk menjalankan query INSERT, UPDATE, DELETE di 'db_user'
        public function queryUser($query) {
            return mysqli_query($this->conn_user, $query);
        }

        // Method untuk escape string di 'db_destinasi'
        public function escapeString($string) {
            return mysqli_real_escape_string($this->conn, $string);
        }

        // Method untuk escape string di 'db_user'
        public function escapeStringUser($string) {
            return mysqli_real_escape_string($this->conn_user, $string);
        }
    }
?>