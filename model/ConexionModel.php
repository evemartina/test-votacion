<?php
class Database {
    private $host = "localhost";
    private $db_name = "votacion";
    private $username = "root";
    private $password = "";
    public $conn;

    /**
	 * La función establece una conexión a una base de datos MySQL usando PDO en PHP.
	 * 
	 *  La función `getConnection()` devuelve el objeto de conexión a la base de datos
	 * `->conn`.
	 */
	public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
