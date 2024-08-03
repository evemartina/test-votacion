
<?php

class RegionModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRegions() {
        $query = "SELECT id, nombre FROM regiones";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $regiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $regiones;
    }
}
?>
