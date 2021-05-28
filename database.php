<?php
class Database {
    private $db_host = "sql200.epizy.com";
    private $db_name = "epiz_28409895_arbeid";
    private $db_username = "epiz_28409895"; 
    private $db_password = "4saeoGtEjjp";

    private $conn;

    public function get_Connection() {
        $this->conn = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);

        if($this->conn->connect_errno) {
            echo "Failed to connect";
            exit();
        }
        return $this->conn;
    }

    public function close_Connection() {
        if($this->conn) {
            $this->conn->close();
        }
        else {
            echo "No connection";
        }
    }
}
?>