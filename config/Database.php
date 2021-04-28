<?php
    class Database {
        private $conn;

        public function connect() {
            $this->conn = null;
            $url = getenv('JAWSDB_MARIA_URL');
            $dbparts = parse_url($url);
            $hostname = $dbparts['host'];
            $username = $dbparts['user'];
            $password = $dbparts['pass'];
            $database = ltrim($dbparts['path'],'/');
            $dsn = "mysql:host={$hostname};dbname={$database}";
            try {
                $this->conn = new PDO($dsn, $username, $password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $error) {
                echo 'Connection Error: ' . $error->getMessage();
            }
            return $this->conn;
        }
    }