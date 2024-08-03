<?php

class ComunaModel {
  /**
   * la función constructora que inicializa una variable privada
   * con una conexión de base de datos pasada como parámetro.
   * 
   *   El parámetro db  en el constructor es  usado para pasar una conexión de base de datos
   * a la clase.
   */
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getComunasByRegion($region_id) {
		$query = "SELECT id, nombre FROM comunas WHERE region_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $region_id);
        $stmt->execute();

        $comunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $comunas;
    }
}
?>
