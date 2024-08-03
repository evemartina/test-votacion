<?php

class CandidatoModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllCandidatos() {
		$query = "SELECT id, nombre FROM candidatos ";
        $stmt = $this->conn->prepare($query);
		$stmt = $this->conn->prepare($query);
        $stmt->execute();

        $candidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $candidatos;
    }
}
?>