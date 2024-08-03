
<?php
class MedioModel{
	private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
	public function getAllMedios() {
        $query = "SELECT id, medio FROM medio_nosotros";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $medios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $medios;
    }
}


?>